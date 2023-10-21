<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Repositories\FacebookRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReportOrderFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var String
     */
    protected $eventType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $eventType)
    {
        $this->order = $order;
        $this->eventType = $eventType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = config('services.facebook.token');

        $pixelId = config('services.facebook.pixel_id');

        $repository = new FacebookRepository($pixelId, $token);

        switch ($this->eventType) {

            case 'purchase':
                $response = $repository->reportPurchase($this->order);
                break;
            
            case 'initiateCheckout':
                $response = $repository->reportInitiateCheckout($this->order);
                break;
            
            default:
                # code...
                break;
        }

        $this->order->apiResponses()->create([
            'caller' => 'ReportOrderFacebookJob::report' . ucfirst($this->eventType),
            'content' => json_encode($response->__toString()),
            'status' => $response->getEventsReceived()
        ]);
    }
}
