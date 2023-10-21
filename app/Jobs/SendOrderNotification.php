<?php

namespace App\Jobs;

use App\Order;
use App\Mail\OrderMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Notifications\TextNotification;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Locate\LocateApiClient;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;

class SendOrderNotification implements ShouldQueue
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

            $order = $this->order;

            try {
                $order->notify(new OrderNotification($order));
            } catch (\Exception $exception) {
                logger($exception->getMessage());
            }

            try {
                $bcc = config('mail.bcc');
                $email = array_shift($bcc);

                Mail::to($email)->bcc($bcc)->send(
                    (new OrderMailable($order))->subject("Your order from Rebelsmuggling.com - Order #{$order->id} - {$order->order_source}")
                );
            } catch (\Exception $exception) {
                logger($exception->getMessage());
            }

        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }
}
