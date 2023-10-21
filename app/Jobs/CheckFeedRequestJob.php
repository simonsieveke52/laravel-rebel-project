<?php

namespace App\Jobs;

use App\Order;
use App\AmazonFeedRequest;
use Illuminate\Bus\Queueable;
use FME\Amazon\AmazonRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckFeedRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var AmazonFeedRequest
     */
    protected $amazonFeedRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AmazonFeedRequest $amazonFeedRequest)
    {
        $this->amazonFeedRequest = $amazonFeedRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $amazon = new AmazonRepository();

        sleep(mt_rand(120, 160));

        $response = $amazon->getClient()->GetFeedSubmissionResult(
            $this->amazonFeedRequest->feed_submission_id
        );

        $this->amazonFeedRequest->markResponseStatus($response);

        $ids = $this->amazonFeedRequest->processedOrders->pluck('id');

        // mark orders as shipped
        if (! $ids->isEmpty()) {
            Order::whereIn('id', $ids)->update([
                'order_status_id' => 2
            ]);
        }

        // $orders = Order::amazon()->whereIn('order_status_id', [1, 3])->whereHas('trackingNumbers')->get()
        // SendTrackingToAmazonJob::dispatch($orders);
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
