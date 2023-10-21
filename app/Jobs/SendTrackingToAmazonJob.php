<?php

namespace App\Jobs;

use App\Order;
use App\AmazonFeedRequest;
use Illuminate\Bus\Queueable;
use FME\Amazon\AmazonRepository;
use App\Jobs\CheckFeedRequestJob;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTrackingToAmazonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    
    /**
     * @var Collection
     */
    protected $orders;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
        
        try {
            $totalUpdated = Order::whereIn('id', $this->orders->pluck('id'))->update([
                'push_tracking' => false,
                'push_tracking_at' => now()
            ]);
        } catch (\Exception $e) {
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            if ($this->orders->isEmpty()) {
                return true;
            }

            $amazon = new AmazonRepository();

            $data = $amazon->prepareTrackingNumbers($this->orders);

            if (! is_array($data) || empty($data)) {
                return true;
            }

            sleep(mt_rand(90, 120));

            logger('SendTrackingToAmazonJob: total data => ' . count($data));

            $response = $amazon->updateTrackingNumber($data);

            if (! is_array($response)) {
                throw new \Exception('SendTrackingToAmazonJob failed due to $response is not array');
            }

            $amazonFeedRequest = AmazonFeedRequest::create([
                'feed_submission_id' => $response['FeedSubmissionId'] ?? '',
                'feed_type' => $response['FeedType'] ?? '',
                'feed_processing_status' => $response['FeedProcessingStatus'] ?? ''
            ]);

            try {
                $amazonFeedRequest->orders()->attach(
                    $this->orders->pluck('id')
                );
            } catch (\Exception $e) {
                logger('SendTrackingToAmazonJob: ' . $e->getMessage());
            }

            logger('SendTrackingToAmazonJob: processed');

            CheckFeedRequestJob::dispatch($amazonFeedRequest)
                ->onQueue('default')
                ->delay(
                    now()->addMinutes(mt_rand(12, 14))
                );

        } catch (\Exception $e) {
            logger('SendTrackingToAmazonJob: ' . $e->getMessage());
            logger('SendTrackingToAmazonJob: failed');
            throw new \Exception($e);
        }
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
