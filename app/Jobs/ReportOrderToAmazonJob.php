<?php

namespace App\Jobs;

use App\Order;
use FME\Amazon\AmazonFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReportOrderToAmazonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $response = AmazonFacade::createFulfillmentOrder($this->order);   
        } catch (\Exception $exception) {
            $response = $exception->getMessage();

            try {
                $this->order->notify(
                    new AmazonOrderFailedNotification($this->order, $exception)
                );
            } catch (\Exception $e) {
                logger($e->getMessage());
            }
        }

        $this->order->refresh();

        $this->order->apiResponses()->create([
            'caller' => 'AmazonFacade::createFulfillmentOrder',
            'content' => json_encode($response),
            'status' => trim($this->order->amazon_fulfillment_channel) === 'AFN'
        ]);
    }
}
