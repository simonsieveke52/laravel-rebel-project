<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Repositories\FacebookRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ReportFacebookEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $eventType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, string $eventType)
    {
        $this->data = $data;
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

            case 'ViewContent':
                $response = $repository->reportViewContent($this->data);
                break;
                
            case 'AddToCart':
                $response = $repository->reportAddToCart($this->data);
                break;
            
            default:
                # code...
                break;
        }

        return $response;
    }
}
