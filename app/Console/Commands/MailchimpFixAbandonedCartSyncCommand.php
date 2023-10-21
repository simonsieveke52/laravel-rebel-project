<?php

namespace App\Console\Commands;

use Exception;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpFixAbandonedCartSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:fix-abandoned-cart-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix abandoned carts with Mailchimp';

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

        $orders = Order::with('products')->confirmed()
            ->where('type', 'direct')
            ->whereNotNull('checkout_url')
            ->get();

        if ($orders->isEmpty()) {
            $this->line('No abandoned carts to sync.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();


        try {
            $mailchimp = new MailchimpRepository();
            $orders->each(function($order) use($mailchimp, $progressBar) {
                $mailchimp->deleteAbandonedCart($order);
                $progressBar->advance();
            });

            $progressBar->finish();
            $this->info("\nMailchimp Abandoned Cart Sync Complete");
        } catch(Exception $e) {
            $progressBar->finish();
            $this->error("\nMailchimp Abandoned Cart Sync Failed");
            logger($e->getMessage());
        }
    }
}
