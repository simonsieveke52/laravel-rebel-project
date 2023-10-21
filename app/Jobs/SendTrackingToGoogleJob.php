<?php

namespace App\Jobs;

use App\TrackingNumber;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTrackingToGoogleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    protected $orders;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
        logger('Start Sent tracking to Google.');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->orders->isEmpty()) {
            logger('Empty Google orders');
            return true;
        }

        $orderMerchantService = app()->make('OrderMerchantService');

        try {

            $resourceArray = Arr::wrap($this->orders->pluck('trackingNumbers')->values());
            $resources = isset($resourceArray[0]) && $resourceArray[0] instanceof TrackingNumber 
                ? $resourceArray 
                : $this->orders->pluck('trackingNumbers')->values()->flatten();

        } catch (\Exception $e) {
            $resources = [];
        }

        if (empty($resources)) {
            throw new \Exception("No tracking numbers to process");
        }

        foreach ($resources as $trackingNumber) {

            if (! $trackingNumber instanceof TrackingNumber) {
                continue;
            }

            $trackingNumber->loadMissing('order');

            logger('Google order: ' . $trackingNumber->order->google_order_id);

            $googleOrder = $orderMerchantService->getOrder($trackingNumber->order->google_order_id);

            $response = $orderMerchantService->setShiplineItems($googleOrder, $trackingNumber);

            if (! ($response instanceof \Google_Service_ShoppingContent_OrdersShipLineItemsResponse)) {
                throw new \Exception('Error durring Google tracking upload');
            }

            $trackingNumber->order->markAsShipped();
        }

        logger('Google orders tracking update done.');
    }

    /**     
     * @return Carbon     
     */  
    public function retryAfter()    
    {  
        return now()->addMinutes(
            (int) round(((2 ** $this->attempts()) - 1 ) / 2)
        );
    }
}
