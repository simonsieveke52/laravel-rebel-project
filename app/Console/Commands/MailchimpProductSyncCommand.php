<?php

namespace App\Console\Commands;

use Exception;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\MailchimpRepository;

class MailchimpProductSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:product-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products with Mailchimp';

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
        if (! config('mailchimp.enabled')) {
            $this->error('Try enabling mailchimp..');
            return 0;
        }

        $mailchimp = new MailchimpRepository();

        $progressBar = $this->output->createProgressBar(Product::count());
        $progressBar->start();

        Product::with('images')->chunk(250, function ($products) use (&$progressBar, $mailchimp) {
            sleep(1);

            foreach ($products as $product) {

                $progressBar->advance();

                try {
                    $mailchimp->createOrUpdateProduct($product);
                } catch(Exception $e) {
                    $this->error("\nFailed to add product $product->id to store\n" . $e->getMessage());
                }
            }
        });

        $progressBar->finish();
        $this->info("\nMailchimp Product Sync Complete");
    }
}
