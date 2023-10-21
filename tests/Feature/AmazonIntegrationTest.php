<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use Tests\TestCase;
use App\TrackingNumber;
use App\AmazonFeedRequest;
use Illuminate\Support\Arr;
use FME\Amazon\AmazonRepository;
use App\Jobs\CheckFeedRequestJob;
use App\Jobs\ReportOrderToAmazonJob;
use App\Jobs\GetAmazonTrackingNumbersJob;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Order::where('type', 'amazon')->update(['amazon_fulfillment_channel' => 'mfn'])

class AmazonIntegrationTest extends TestCase
{
    /**
     * @return void
     */
    public function it_can_list_orders()
    {
        $amazon = new AmazonRepository();
        
        $listOrders = $amazon->listOrders();

        $this->assertTrue(is_array($listOrders));
    }

    /**
     * @return void
     */
    public function it_can_create_order_from_amazon()
    {
        $data = [
            "LatestShipDate" => "2020-05-19T06:59:59Z",
            "OrderType" => "StandardOrder",
            "PurchaseDate" => "2020-05-04T21:01:11.895Z",
            "BuyerEmail" => "9q0l1jmwz52xht6@marketplace.amazon.com",
            "AmazonOrderId" => "114-7252123-5338617",
            "LastUpdateDate" => "2020-05-04T21:31:39.023Z",
            "IsReplacementOrder" => "false",
            "NumberOfItemsShipped" => "0",
            "ShipServiceLevel" => "Std US D2D Dom",
            "OrderStatus" => "Unshipped",
            "SalesChannel" => "Amazon.com",
            "ShippedByAmazonTFM" => "false",
            "IsBusinessOrder" => "false",
            "NumberOfItemsUnshipped" => "1",
            "LatestDeliveryDate" => "2020-05-27T06:59:59Z",
            "PaymentMethodDetails" => [
                "PaymentMethodDetail" => "Standard",
            ],
            "IsGlobalExpressEnabled" => "false",
            "IsSoldByAB" => "false",
            "EarliestDeliveryDate" => "2020-05-15T07:00:00Z",
            "IsPremiumOrder" => "false",
            "OrderTotal" => [
                "Amount" => "86.22",
                "CurrencyCode" => "USD",
            ],
            "EarliestShipDate" => "2020-05-12T07:00:00Z",
            "MarketplaceId" => "ATVPDKIKX0DER",
            "FulfillmentChannel" => "MFN",
            "PaymentMethod" => "Other",
            "ShippingAddress" => [
                "City" => "APPLE VALLEY",
                "PostalCode" => "92308-0479",
                "isAddressSharingConfidential" => "false",
                "StateOrRegion" => "CA",
                "CountryCode" => "US",
            ],
            "IsPrime" => "false",
            "ShipmentServiceLevelCategory" => "Standard",
        ];

        $amazon = new AmazonRepository();

        $order = $amazon->createOrder($data);

        $this->assertTrue($order->exists);
    }

    /**
     * @return void
     */
    public function it_can_get_order() 
    {
        // 114-1578945-1246649
        // 114-7578455-0581004 
        // 114-6112779-2441055

        $testOrder = '114-8270886-4017012';

        $amazon = new AmazonRepository();

        $order = $amazon->getOrder($testOrder);

        $orderItems = $amazon->getClient()->ListOrderItems($testOrder);

        $this->assertTrue(is_array($order));
    }

    /**
     * @return void
     */
    public function it_can_rebuild_order_details()
    {
        $orders = Order::amazon()->doesntHave('products')->get();
        echo $orders->count() . PHP_EOL;
        $orders->each(function($order) {
            try {
                $amazon = new AmazonRepository();
                echo 'Order ID: ' . $order->id . PHP_EOL;
                $amazon->buildOrderDetails($order);
                $sleep = [60, 120][mt_rand(0, 1)];
                echo 'sleep ... '.$sleep.'s' . PHP_EOL;
                sleep($sleep);
            } catch (\Exception $e) {
                
            }
        });
    }

    /**
     * @return void
     */
    public function it_can_import_orders()
    {
        $amazon = new AmazonRepository();
        $listOrders = $amazon->listOrders();
        $created = 0;

        foreach ($listOrders as $data) {
            try {
                Order::where('amazon_order_id', $data['AmazonOrderId'])->firstOrFail();
            } catch (\Exception $e) {
                try {
                    $order = $amazon->createOrder($data);
                    $created++;
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }

        $this->assertTrue($created > 0);
    }

    /**
     * @return void
     */
    public function it_can_sync_quantity_and_price()
    {
        $reject = function ($item) {
            if (! is_array($item)) {
                return true;
            }

            if (count($item) === 0) {
                return true;
            }

            return false;
        };

        $amazon = new AmazonRepository();

        Product::fba()->chunk(20, function ($products) use ($amazon, $reject) {

            $skus = $products->pluck('sku')->toArray();

            collect(
                $amazon->getClient()->GetMyPriceForSKU($skus)
            )
            ->reject($reject)
            ->each(function ($item) use ($products) {
                $product = $products->where('sku', $item['SellerSKU'])->first();
                $product->price = $item['BuyingPrice']['ListingPrice']['Amount'];
                $product->save();
            });

            collect(
                $amazon->getClient()->ListInventorySupply($skus)
            )
            ->reject($reject)
            ->each(function ($item) use ($products) {
                $product = $products->where('sku', $item['SellerSKU'])->first();
                $product->quantity = $item['TotalSupplyQuantity'];
                $product->save();
            });
        });
    }

    /**
     * @return void
     */
    public function it_can_create_createFulfillmentOrder()
    {
        $amazon = new AmazonRepository();

        $order = Order::find(12004);

        ReportOrderToAmazonJob::dispatchNow($order);

        $this->assertTrue($order->amazon_fulfillment_channel === 'AFN');
    }

    /**
     * @test
     * @return void
     */
    public function it_can_sync_tracking()
    {
        $amazon = new AmazonRepository();

        $order = Order::find(12004);

        $response = $amazon->GetFulfillmentOrder([
            // 'SellerFulfillmentOrderId' => $order->id
            'SellerFulfillmentOrderId' => 'CONSUMER-20200202-104816'
        ]);

        if (count($response['FulfillmentShipment']) === 0) {
            throw new \Exception("No tracking data found for order Id {$order->id}");
        }

        $packages = isset($response['FulfillmentShipment']['member']['FulfillmentShipmentPackage']['member'][0]) 
            ? $response['FulfillmentShipment']['member']['FulfillmentShipmentPackage']['member'] 
            : [$response['FulfillmentShipment']['member']['FulfillmentShipmentPackage']['member']];

        if (count($packages) === 0) {
            throw new \Exception("No packages for this shipment - order Id {$order->id}");
        }

        foreach ($packages as $package) {

            $trackingNumber = TrackingNumber::create([
                'number' => $package['TrackingNumber'],
                'order_id' => $order->id,
                'quantity' => collect($response['FulfillmentShipment']['member']['FulfillmentShipmentItem']['member'])->where('PackageNumber', $package['PackageNumber'])->sum('Quantity'),
                'name' => $package['CarrierCode'],
                'lot_number' => $package['PackageNumber'],
            ]);

            try {
                $trackingNumber->notify(new TrackingNumberNotification($order));
            } catch (\Exception $exception) {
                Notification::route('slack', config('services.slack.order_notification_webhook'))
                    ->notify(new TextNotification($exception->getMessage()));
            }

            event(new TrackingNumberCreatedEvent($order, $trackingNumber));
        }
    }

    /**
     * @return void
     */
    public function it_can_get_shipped_orders()
    {
        $amazon = new AmazonRepository();

        $response = $amazon->getClient()->ListAllFulfillmentOrders(now()->subDays(10));

        dd($response);
    }

    /**
     * @return void
     */
    public function it_can_import_orders_and_update_tracking()
    {
        $amazon = new AmazonRepository();
        $listOrders = $amazon->listOrders(now()->subDays(15));
        $orders = collect();
        
        foreach ($listOrders as $data) {
            try {
                $order = Order::where('amazon_order_id', $data['AmazonOrderId'])->where('order_status_id', '!=', 2)->firstOrFail();
                $orders->push($order);
            } catch (\Exception $e) {
                
            }
        }

        $data = $amazon->prepareTrackingNumbers($orders);
        $response = $amazon->updateTrackingNumber($data);

        $amazonFeedRequest = AmazonFeedRequest::create([
            'feed_submission_id' => $response['FeedSubmissionId'],
            'feed_type' => $response['FeedType'],
            'feed_processing_status' => $response['FeedProcessingStatus']
        ]);

        $amazonFeedRequest->orders()->attach($orders->pluck('id'));

        CheckFeedRequestJob::dispatch($amazonFeedRequest)
                ->onQueue('default')
                ->delay(
                    now()->addMinutes(mt_rand(15, 20))
                );

        $this->assertTrue($created > 0);
    }

    public function it_can_update_orders_to_shipped()
    {
        $amazon = new AmazonRepository();
        $listOrders = $amazon->listOrders(now()->subDays(15), false, ['Shipped']);
        $orders = collect();
        foreach ($listOrders as $data) {
            try {
                $order = Order::where('amazon_order_id', $data['AmazonOrderId'])->where('order_status_id', '!=', 2)->firstOrFail();
                $order->order_status_id = 2;
                $order->save();
            } catch (\Exception $e) {
                
            }
        }
    }

    /**
     * @return void
     */
    public function it_can_update_tracking()
    {
        $amazon = new AmazonRepository();

        $orders = Order::whereIn('amazon_order_id', [
            // '114-1578945-1246649',
            // '114-7578455-0581004',
            '114-6112779-2441055'
        ])->get();

        try {

            $data = $amazon->prepareTrackingNumbers($orders);

            $response = $amazon->updateTrackingNumber($data);

            $amazonFeedRequest = AmazonFeedRequest::create([
                'feed_submission_id' => $response['FeedSubmissionId'],
                'feed_type' => $response['FeedType'],
                'feed_processing_status' => $response['FeedProcessingStatus']
            ]);

            dump($amazonFeedRequest->id);
            dump($response);

            $amazonFeedRequest->orders()->attach($orders);

            $this->assertTrue(! $amazonFeedRequest->orders->isEmpty());

        } catch (\Exception $e) {
            $this->assertTrue(false);
        }
    }

    /**
     * @return void
     */
    public function it_can_check_feed_request_after_update()
    {
        $amazonFeedRequest = AmazonFeedRequest::orderBy('id', 'desc')->first();

        $amazon = new AmazonRepository();
        $response = $amazon->getClient()->GetFeedSubmissionResult($amazonFeedRequest->feed_submission_id);

        dump($response);

        $amazonFeedRequest->markResponseStatus($response);

        $this->assertTrue(is_array($response));
    }

    /**
     * @return void
     */
    public function it_can_update_products() 
    {
        $amazon = new AmazonRepository();

        $products = Product::where('sku', 'FR-FRFR-8995')->take(1)->get();

        $response = $amazon->updateProducts($products);

        $amazonFeedRequest = AmazonFeedRequest::create([
            'feed_submission_id' => $response['FeedSubmissionId'],
            'feed_type' => $response['FeedType'],
            'feed_processing_status' => $response['FeedProcessingStatus']
        ]);

        $amazonFeedRequest = AmazonFeedRequest::find(4);

        $response = $amazon->getClient()->GetFeedSubmissionResult($amazonFeedRequest->feed_submission_id);

        $amazonFeedRequest->markResponseStatus($response);

        dd($response);
    }
}
