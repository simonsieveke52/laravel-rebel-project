<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TrackingNumberCreatedListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;
        
        $order->markAsShipped();

        try {
            $trackingNumber = $event->trackingNumber;

            $response = app(LocateRepository::class)
                ->createReceipt([
                    'order_id' => [$order->api_purchase_order_id],
                    'shipping_cost' => $order->shipping_cost,
                    'tracking_number' => $trackingNumber->number
                ]);

            $trackingNumber->api_receipt_id = $response->id;
            $trackingNumber->api_receipt_created_at = date('Y-m-d H:i:s');
            $trackingNumber->save();

            $response = app(LocateRepository::class)
                ->makeRequest("receipt/{$trackingNumber->api_receipt_id}?embed=lines", "GET", true);

            foreach (data_get($response, 'lines.*') as $line) {
                app(LocateRepository::class)->markReceiptReceived((int) $line['id'], [
                    'qty' => $line['qty']
                ]);
            }

            app(LocateRepository::class)
                ->makeRequest("/receipt/{$trackingNumber->api_receipt_id}/finish", "POST");
            
        } catch (\Exception $e) {
        }
    }
}
