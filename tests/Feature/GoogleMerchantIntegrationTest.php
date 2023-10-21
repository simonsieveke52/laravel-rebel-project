<?php

namespace Tests\Feature;

use App\Order;
use Tests\TestCase;
use App\Events\OrderCreateEvent;
use FME\GoogleMerchant\GoogleProducts;
use FME\GoogleMerchant\OrderMerchantService;
use Illuminate\Foundation\Testing\WithFaker;
use App\Custom\TrackingNumbers\TrackingNumber;
use FME\EloquenceCsvFeed\Handlers\ProductHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoogleMerchantIntegrationTest extends TestCase
{
    // download orders, upload tracking, track what orders dont have tracking

    /**
     * @return void
     */
    public function it_can_get_config()
    {
        $this->assertTrue(
            is_array(config('google-api')) && !empty(config('google-api'))
        );
    }
    
	/**
     * @return void
     */
    public function it_can_find_product_from_google()
    {
        $googleProduct = new GoogleProducts();

        $product = $googleProduct->getProduct(24802688);

        $this->assertTrue($product instanceof \Google_Service_ShoppingContent_Product);
    }

    /**
     * @return void
     */
    public function it_can_create_google_product_instance()
    {
        $googleProduct = new GoogleProducts();

        $this->assertTrue($googleProduct instanceof GoogleProducts);
    }

    /**
     * @return void
     */
    public function it_can_insert_product_using_google_api()
    {
        $product = Product::find(24802688);

        tap(new GoogleProducts(), function($googleProduct) use ($product){
            return $googleProduct->createProduct( 
                (new ProductHandler())->transform($product) 
            );
        });

        $googleProduct = (new GoogleProducts())->getProduct($product->id);

        $this->assertTrue($googleProduct instanceof \Google_Service_ShoppingContent_Product);
    }

    /**
     * @return void
     */
    public function it_can_get_products_list()
    {
    	$googleProduct = new GoogleProducts();

    	$products = $googleProduct->listProducts();

        $this->assertTrue(!is_null($products));
    }

    /**
     * @return void
     */
    public function it_can_update_product_from_api()
    {
    	$data = (new ProductHandler())->transform(
    		Product::find(24802688)
    	);

    	$newTitle = 'Title updated';
    	$currentTitle = $data['title'];

    	$googleProductInstance = (new GoogleProducts());

    	$googleProduct = $googleProductInstance->getProduct($data['id']);

    	$googleProduct->setTitle($newTitle);

		$googleProductInstance->updateProduct($googleProduct);

		$googleProduct = $googleProductInstance->getProduct($data['id']);

        $this->assertTrue($googleProduct->getTitle() == $newTitle);
    }

    /**
     * @return void
     */
    public function it_can_delete_product_from_api()
    {
    	$googleProduct = new GoogleProducts();

		$product = Product::orderBy('id', 'desc')->first();

		$product->delete();

		sleep(15);

		try {
			$googleProduct->getProduct($product->id);
		} catch (\Google_Service_Exception $e) {
			$this->assertTrue($e instanceof \Google_Service_Exception);
		}
    }

    /**
     * @return void
     */
    public function it_can_get_orders_list()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        $parameters['acknowledged'] = false;

        $orders = $orderMerchantService->listOrders($parameters);

        $status = [];

        collect($orders)->each(function($googleOrder) use (&$status) {
            $status[] = $googleOrder->getStatus();
        });

        dd(array_unique($status));

        $this->assertTrue(!empty($orders));
    }

    /**
     * @return get order from api
     */
    public function it_can_get_order()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        $order = $orderMerchantService->getOrder('G-SHP-0226-16-1382');

        $this->assertTrue($order instanceof \Google_Service_ShoppingContent_Order);
    }

    /**
     * @return void
     */
    public function it_can_import_google_orders()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        $imported = [];

        $parameters['acknowledged'] = false;
        $googleOrders = $orderMerchantService->listOrders($parameters);

        foreach ($googleOrders as $googleOrder) {
            try {
                $order = $orderMerchantService->createOrder($googleOrder);
                $orderMerchantService->updateMerchantOrderId($googleOrder->getId(), $order->id);
                $orderMerchantService->setAcknowledged($googleOrder->getId());
                $imported[] = $order;
            } catch (\Exception $e) {
            }
        }

        $this->assertTrue(count($imported) === count($googleOrders));
    }

    /**
     * @return void
     */
    public function it_can_create_and_update_order()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        $googleOrder = $orderMerchantService->getOrder('G-SHP-0226-16-1382');

        $order = $orderMerchantService->createOrder($googleOrder);

        $orderMerchantService->updateMerchantOrderId($googleOrder->getId(), $order->id);
        $orderMerchantService->setAcknowledged($googleOrder->getId());

        $this->assertTrue($order instanceof Order);
        $this->assertTrue($order->exists);
        $this->assertTrue($order->order_status_id !== 1);
        $this->assertTrue(! $order->products->isEmpty());
        $this->assertTrue(! $order->shippings->isEmpty());
        $this->assertTrue(! $order->addresses->isEmpty());
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_shipping()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        $order = Order::find(14940);

        $trackingNumber = $order->trackingNumbers->first();

        $googleOrder = $orderMerchantService->getOrder($order->google_order_id);

        $return = $orderMerchantService->setShiplineItems($googleOrder, $trackingNumber);

        $this->assertTrue($return instanceof \Google_Service_ShoppingContent_OrdersShipLineItemsResponse);
    }

    /**
     * @return void
     */
    public function it_can_set_shipping()
    {
        $orderMerchantService = app()->make('OrderMerchantService');

        // $order = Order::find(32086 - 12500);

        // $trackingNumber = $order->trackingNumbers->first();

        // G-SHP-1071-87-4142
        // G-SHP-7109-57-6414

        $googleOrder = $orderMerchantService->getOrder('G-SHP-2877-83-0556');

        dd($googleOrder);

        $product = $order->products->where('id', 123931726)->first();

        $quantity = $product->pivot->quantity / ($product->pivot->min_order_quantity ?? 1) ?? 1;

        $return = $orderMerchantService->setShiplineItem($googleOrder, $trackingNumber, $product, $quantity);
        
        $this->assertTrue($return instanceof \Google_Service_ShoppingContent_OrdersShipLineItemsResponse);
    }
}
