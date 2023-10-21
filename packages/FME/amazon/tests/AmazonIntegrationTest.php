<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use MCS\MWSClient;
use Tests\TestCase;
use FME\Amazon\AmazonFacade;
use App\Mail\AmazonOrderMailable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\AmazonOrderFailedNotification;

class AmazonIntegrationTest extends TestCase
{
    // /**
    //  * @return void
    //  */
    // public function test_can_create_amazon_instance()
    // {
    //     $client = AmazonFacade::getClient();
    //     $this->assertTrue($client instanceof MWSClient);
    // }

    // /**
    //  * @return void
    //  */
    // public function test_can_list_all_fulfillment_orders()
    // {
    // 	$from = now()->subDay();

    // 	$orders = AmazonFacade::listAllFulfillmentOrders($from);

    // 	$this->assertTrue(is_array($orders));
    // }

    /**
     * @return void
     */
    public function test_can_create_fulfillment_order()
    {
    	$order = Order::first();

        // MCF-Syrup-1-EC

        try {

            $response = AmazonFacade::createFulfillmentOrder($order);   
            
            if ( is_string($response) ) {
                $order->markAsReported()
                    ->markAsShipped();
            }

        } catch (\Exception $exception) {
            $order->notify(new AmazonOrderFailedNotification($order, $exception));
            $response = $exception->getMessage();
        }

        $order->apiResponses()->create([
            'caller' => 'AmazonFacade::createFulfillmentOrder',
            'content' => json_encode($response ?? ''),
            'status' => (int) $order->order_status_id === 2
        ]);

        // failed to set order as shipped
        if ((int) $order->order_status_id !== 2) {
            throw new \Exception($response);
        }
    }
}
