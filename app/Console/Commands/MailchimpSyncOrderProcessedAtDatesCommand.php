<?php

namespace App\Console\Commands;

use Exception;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpSyncOrderProcessedAtDatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:sync-processed-at-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders processed at dates with Mailchimp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        ini_set('memory_limit', -1);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('mailchimp.enabled')) {
            $this->error('Try enabling mailchimp..');
            return 0;
        }

        $orders = Order::whereNotNull('mc_cid')->whereNotNull('confirmed_at')->get();

        if ($orders->isEmpty()) {
            $this->line('No orders to sync.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();


        try {
            $mailchimp = new MailchimpRepository();
            $orders->each(function($order) use($mailchimp, $progressBar) {
                $url = config('mailchimp.api_url') . 'ecommerce/stores/';
                $url .= $mailchimp->store . '/orders/' . $order->id;

                $response = $mailchimp->httpClient('PATCH', $url, [
                    'processed_at_foreign' => $order->confirmed_at->toIso8601String()
                ]);

                if ($response->failed()) {
                    logger('Failed to update order: ' . $order->id);
                }

                $progressBar->advance();
            });

            $progressBar->finish();
            $this->info("\nMailchimp Order Processed At Dates Update Complete");
        } catch(Exception $e) {
            $progressBar->finish();
            $this->error("\nMailchimp Order Processed At Dates Update Failed");
            logger($e->getMessage());
        }
    }
}
