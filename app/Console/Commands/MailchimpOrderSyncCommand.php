<?php

namespace App\Console\Commands;

use Exception;
use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpOrderSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:order-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders with Mailchimp';

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

        $orders = Order::with('products')
            ->confirmed()
            ->whereHas('products')
            ->where('type', 'direct')
            ->get();

        if ($orders->isEmpty()) {
            $this->line('No orders to sync.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();

        try {
            $failures = [];
            $mailchimp = new MailchimpRepository();
            $orders->each(function($order) use($mailchimp, $progressBar, &$failures) {
                if (! $mailchimp->syncOrder($order)) {
                    array_push($failures, $order->id);
                }
                $progressBar->advance();
            });

            $progressBar->finish();
            $this->info("\nMailchimp Order Sync Complete");
            if (!empty($failures)) {
                $this->error("\nFailed to sync:");
                $this->error(print_r($failures));
            }
        } catch(Exception $e) {
            $progressBar->finish();
            $this->error("\nMailchimp Order Sync Failed");
            logger($e->getMessage());
        }
    }
}
