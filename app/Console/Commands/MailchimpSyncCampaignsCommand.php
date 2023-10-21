<?php

namespace App\Console\Commands;

use Exception;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpSyncCampaignsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:sync-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync abandoned cart campaigns with Mailchimp';

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

        $orders = Order::confirmed()->where('type', 'direct')->whereNotNull('checkout_url')->whereNull('mc_cid')->whereRaw("TIMESTAMPDIFF(HOUR, created_at, confirmed_at) >= 1")->get();

        if ($orders->isEmpty()) {
            $this->line('No abandoned carts to sync.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();


        try {
            $mailchimp = new MailchimpRepository();
            $orders->each(function($order) use($mailchimp, $progressBar) {
                $url = config('mailchimp.api_url') . 'ecommerce/stores/';
                $url .= $mailchimp->store . '/orders/' . $order->id;

                if ($order->discount_id !== null &&
                    $order->appliedDiscount()->where('discount_type', 'Discount on order')->exists()) {
                    $campaign_id = config('mailchimp.abandoned_cart.campaign_emails')[2];
                } else {
                    $campaign_id = config('mailchimp.abandoned_cart.campaign_emails')[0];
                }

                $response = $mailchimp->httpClient('PATCH', $url, ['campaign_id' => $campaign_id]);
                if ($response->failed()) {
                    logger('Failed to update order: ' . $order->id);
                } else {
                    $order->update(['mc_cid' => $campaign_id]);
                }
                $progressBar->advance();
            });

            $progressBar->finish();
            $this->info("\nMailchimp Abandoned Cart Revenue Tracking Sync Complete");
        } catch(Exception $e) {
            $progressBar->finish();
            $this->error("\nMailchimp Abandoned Cart Revenue Tracking Sync Failed");
            logger($e->getMessage());
        }
    }
}
