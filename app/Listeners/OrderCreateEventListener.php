<?php

namespace App\Listeners;

use App\Order;
use App\Mail\OrderMailable;
use App\Jobs\MailchimpApiJob;
use App\Events\OrderCreateEvent;
use App\Jobs\ReportOrderAdwordsJob;
use App\Jobs\SendOrderNotification;
use App\Jobs\ReportOrderFacebookJob;
use App\Jobs\ReportOrderToAmazonJob;
use Illuminate\Support\Facades\Mail;
use App\Repositories\OrderRepository;
use App\Notifications\TextNotification;
use App\Notifications\OrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Locate\LocateRepository;
use App\Notifications\OrderReportedNotification;

class OrderCreateEventListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            SendOrderNotification::dispatch($event->order)->onQueue('default');
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }
        
        if (! $event->order->products->where('is_fba', true)->isEmpty()) {
            try {
                ReportOrderToAmazonJob::dispatch($event->order)->onQueue('high');
            } catch (\Exception $e) {
                logger($e->getMessage());       
            }
        }

        try {
            ReportOrderAdwordsJob::dispatch($event->order)->onQueue('high');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        try {
            ReportOrderFacebookJob::dispatch($event->order, 'purchase')->onQueue('high');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        try {
            MailchimpApiJob::dispatch($event->order)->onQueue('default');
        } catch (\Exception $e) {
            logger($e->getMessage());      
        }
    }
}
