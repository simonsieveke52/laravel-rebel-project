<?php

namespace FME\GoogleMerchant;

use App\Order;
use App\State;
use App\Address;
use App\Product;
use App\Shipping;
use App\TrackingNumber;
use Illuminate\Support\Str;
use App\Repositories\OrderRepository;
use App\Repositories\AddressRepository;
use FME\GoogleMerchant\Contract\GoogleMerchantContract;

class OrderMerchantService extends GoogleMerchantContract
{
	/**
	 * Get order
	 * 
	 * @return array <acknowledged = false>
	 */
	public function listOrders(array $parameters)
	{
        $orders = [];

        $ordersResources = $this->session->service->orders->listOrders(
        	$this->session->merchantId, $parameters
        );

        while (!empty($ordersResources->getResources())) 
        {
            foreach ($ordersResources->getResources() as $order) {
            	$orders[] = $order;
            }

            // If the result has a nextPageToken property then there are more pages
            // available to fetch
            if (empty($ordersResources->getNextPageToken())) {
                break;
            }

            // You can fetch the next page of results by setting the pageToken
            // parameter with the value of nextPageToken from the previous result.
            $parameters['pageToken'] = $ordersResources->nextPageToken;

            $ordersResources = $this->session->service->orders->listOrders(
                $this->session->merchantId, $parameters
            );
        }

        return $orders;
	}

	/**
	 * Get order
	 * @param  string $orderId
	 * @return \Google_Service_ShoppingContent_Order
	 */
	public function getOrder(string $orderId)
	{
		return $this->session->service->orders->get($this->session->merchantId, $orderId);
	}

	/**
	 * Update order
	 * 
	 * @param  \Google_Service_ShoppingContent_Order $order
	 */
	public function setAcknowledged(string $orderId) 
    {
    	$acknowledge = new \Google_Service_ShoppingContent_OrdersAcknowledgeRequest();

    	$acknowledge->setOperationId(time() . mt_rand(0, 100));

        return $this->session->service->orders->acknowledge($this->session->merchantId, $orderId, $acknowledge);
    }

	/**
	 * Update order
	 * 
	 * @param  \Google_Service_ShoppingContent_Order $order
	 */
	public function updateMerchantOrderId(string $orderId, string $merchantOrderId) 
    {
    	$merchantRequest = new \Google_Service_ShoppingContent_OrdersUpdateMerchantOrderIdRequest();

    	$merchantRequest->setMerchantOrderId($merchantOrderId);

    	$merchantRequest->setOperationId(time() . mt_rand(101, 200));

        return $this->session->service->orders->updatemerchantorderid($this->session->merchantId, $orderId, $merchantRequest);
    }

    /**
     * Get mapped google products items
     * 
     * @param  \Google_Service_ShoppingContent_Order $order
     * @return array
     */
    public function getMappedLineItemsProducts(\Google_Service_ShoppingContent_Order $order)
    {
        $lineItems = collect($order->getLineItems())->map(function($item){

            $productId = $item->getProduct()->getOfferId();

            return (Object)[
                'id' => $item->getId(),
                'product_id' => $productId
            ];
        });

        return $lineItems->pluck('id', 'product_id')->toArray();
    }

    /**
     * Set line item shipped
     * 
     * @param \Google_Service_ShoppingContent_Order $order         
     * @param TrackingNumber                        $trackingNumber
     * @param Product                               $product       
     * @param int|integer                           $quantity      
     */
    public function setShiplineItem(
        \Google_Service_ShoppingContent_Order $order, 
        TrackingNumber $trackingNumber, 
        Product $product, 
        int $quantity = 1
    )
    {
        $request = new \Google_Service_ShoppingContent_OrdersShipLineItemsRequest();
        $request->setOperationId(time() . mt_rand(201, 300) . $product->id);

        $mappedLineItems = $this->getMappedLineItemsProducts($order);

        $shippingInfo = new \Google_Service_ShoppingContent_OrdersCustomBatchRequestEntryShipLineItemsShipmentInfo();
        $shippingInfo->setCarrier($trackingNumber->name);
        $shippingInfo->setShipmentId($trackingNumber->id . '-' . $product->id);
        $shippingInfo->setTrackingId($trackingNumber->number);

        $request->setShipmentInfos([$shippingInfo]);

        if (!isset($mappedLineItems[$product->sku])) {
            return false;
        }

        $lineItemId = $mappedLineItems[$product->sku];

        $item = new \Google_Service_ShoppingContent_OrderShipmentLineItemShipment();
        $item->setLineItemId($lineItemId);
        $item->setQuantity($quantity);

        $request->setLineItems([$item]);

        return $this->session->service->orders->shiplineitems($this->session->merchantId, $order->getId(), $request);
    }

    /**
     * Request google api from shiplineItems
     * 
     * @param string $orderId
     */
    public function setShiplineItems(\Google_Service_ShoppingContent_Order $order, TrackingNumber $trackingNumber)
    {
        $request = new \Google_Service_ShoppingContent_OrdersShipLineItemsRequest();
        $request->setOperationId(time() . mt_rand(201, 300));

        $mappedLineItems = $this->getMappedLineItemsProducts($order);

        $shippingInfo = new \Google_Service_ShoppingContent_OrdersCustomBatchRequestEntryShipLineItemsShipmentInfo();
        $shippingInfo->setCarrier($trackingNumber->name);
        $shippingInfo->setShipmentId($trackingNumber->id);
        $shippingInfo->setTrackingId($trackingNumber->number);

        $request->setShipmentInfos([$shippingInfo]);

        $trackingNumber->loadMissing('order.products');

        $items = $trackingNumber->order->products->map(function($product) use ($mappedLineItems){

            if (! isset($mappedLineItems[$product->sku])) {
                return $product;
            }

            $lineItemId = $mappedLineItems[$product->sku];

            $item = new \Google_Service_ShoppingContent_OrderShipmentLineItemShipment();
            $item->setLineItemId($lineItemId);
            $item->setQuantity($product->pivot->quantity);

            return $item;
        })
        ->reject(function($product){
            return $product instanceof Product;
        });

        if ($items->isEmpty()) {
            throw new \Exception("No items found for this shipment");
        }

        $request->setLineItems(
            $items->values()->toArray()
        );

        return $this->session->service->orders->shiplineitems(
            $this->session->merchantId, $order->getId(), $request
        );
    }
    
    /**
     * @param Order                                 $order      
     * @param \Google_Service_ShoppingContent_Order $googleOrder
     */
    protected function setOrderDetails(\Google_Service_ShoppingContent_Order $googleOrder, Order $order)
    {
        try {
            $order->confirmed = true;
            $order->confirmed_at = now();
            $order->mailed = true;
            $order->mailed_at = now();
            $order->payment_method = 'google';
            $order->type = 'google';
            $order->google_order_id = $googleOrder->getId();
            $order->shipping_cost = $googleOrder->getShippingCost()->getValue();
            $order->tax = $googleOrder->getNetTaxAmount()->getValue();
            $order->total = $googleOrder->getNetPriceAmount()->getValue() + $order->tax;
        } catch (\Exception $e) {
            
        }

        try {
            $delivery = $googleOrder->getDeliveryDetails();
            $order->phone = isset($delivery) && ! is_null($delivery) ? $delivery->getPhoneNumber() : null;
        } catch (\Exception $e) {
            
        }

        try {

            $customer = $googleOrder->getCustomer();

            if (is_null($customer)) {
                throw new \Exception("Google customer is not set.");
            }

            $marketing = $customer->getMarketingRightsInfo();

            $order->name = $customer->getFullName();
            $order->email = $marketing->getMarketingEmailAddress() ?? null;
        } catch (\Exception $e) {
            
        }

        $order->save();

        return $order;
    }

    /**
     * @param \Google_Service_ShoppingContent_Order $googleOrder
     * @return Collection
     */
    protected function mapCartItems(\Google_Service_ShoppingContent_Order $googleOrder)
    {
        return collect($googleOrder->getLineItems())->map(function($item) {

            $googleProduct = $item->getProduct();
            $key = $googleProduct->getOfferId();

            try {
                $product = Product::where('sku', $key)->withoutGlobalScopes()->firstOrFail();
            } catch (\Exception $e) {
                
            }

            return (Object) [
                'sku' => $key,
                'id' => isset($product) ? $product->id : null,
                'price' => $googleProduct->getPrice()->getValue(),
                'qty' => $item->getQuantityOrdered(),
                'quantity' => $item->getQuantityOrdered(),
                'options' => collect(),
                'product' => isset($product) ? $product : null
            ];
        }); 
    }

    /**
     * @param  \Google_Service_ShoppingContent_Order $googleOrder
     * @return bool
     */
    protected function createAddress(\Google_Service_ShoppingContent_Order $googleOrder, $type = 'billing')
    {
        $delivery = $googleOrder->getDeliveryDetails();

        $googleAddress = $type === 'billing'
            ? $googleOrder->getBillingAddress() 
            : $delivery->getAddress();

        if (is_null($googleAddress)) {
            throw new \Exception("Failed to get google address. OrderMerchantService@createAddress");
        }

        $state = State::whereAbv($googleAddress->getRegion())
            ->firstOrFail();

        $streetAddress = $googleAddress->getStreetAddress();

        if (empty($streetAddress)) {
            $streetAddress = $googleAddress->getFullAddress();
        }

        return Address::create([
            'country_id' => 1,
            'address_1' => $streetAddress[0] ?? '',
            'address_2' => $streetAddress[1] ?? '',
            'zipcode' => $googleAddress->getPostalCode() ?? '',
            'state_id' => $state->id ?? null,
            'city' => $googleAddress->getLocality(),
            'type' => $type,
        ]);
    }

    /**
     * @param Order $order      
     * @param \Google_Service_ShoppingContent_Order $googleOrder
     * @return bool
     */
    protected function setOrderStatus(\Google_Service_ShoppingContent_Order $googleOrder, Order $order)
    {
        switch ($googleOrder->getStatus()) {

            case 'pendingShipment':
                $order->markAsNew();
                return true;

            case 'canceled':
                $order->markAsCanceled();
                return true;
            
            case 'shipped':
            case 'completed':
            case 'delivered': 
                $order->markAsShipped();
                return true;
            
            default:
                # code...
                break;
        }

        return false;
    }

    /**
     * Create new order from api order data
     * 
     * @param  \Google_Service_ShoppingContent_Order $order
     * @return Order
     */
    public function createOrder(\Google_Service_ShoppingContent_Order $googleOrder): Order
    {
        try {
            return Order::where('google_order_id', $googleOrder->getId())
                ->whereNotNull('order_status_id')
                ->firstOrFail();

        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        $order = new Order();

    	$order = $this->setOrderDetails($googleOrder, $order);

        $catItems = $this->mapCartItems($googleOrder);

        $catItems->each(function ($item) use ($order) {

            if (is_null($item->product)) {
                return true;
            }

            $order->products()->attach($item->product, [
                'quantity' => $item->quantity,
                'price'    => $item->price,
                'options'  => '[]'
            ]);
        });

        $order->subtotal = $catItems->reduce(function ($total, $item) {
            return $total + ($item->quantity * $item->price);
        }, 0);

        $order->total = $order->subtotal + $order->shipping_cost + $order->tax;
        $order->save();

        foreach (['billing', 'shipping'] as $addressType) {
            try {
                $address = $this->createAddress($googleOrder, $addressType);
                $order->addresses()->save($address);
            } catch (\Exception $e) {
                
            }
        }

        $this->setOrderStatus($googleOrder, $order);

        $shippingOptions = Shipping::remember(60 * 60 * 60)->get();

        foreach ($shippingOptions as $shipping) {

            if ($order->products->where('is_frozen', $shipping->is_frozen)->isEmpty()) {
                continue;
            }

            $order->shippings()->attach($shipping, [
                'cost'    => $shipping->cost,
                'is_frozen'  => $shipping->is_frozen
            ]);
        }

        return $order;
    }
}