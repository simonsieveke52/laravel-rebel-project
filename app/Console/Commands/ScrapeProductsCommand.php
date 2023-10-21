<?php

namespace App\Console\Commands;

use App\Product;
use App\Jobs\ScrapeProductsJob;
use Illuminate\Console\Command;
use App\Repositories\Unbxd\UnbxdRepository;

class ScrapeProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:scrape-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and update product column from API';

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
        $chunkSize = 100;

        $progressBar = $this->output->createProgressBar(
            (int) (Product::withoutGlobalScopes()->count() / $chunkSize)
        );

        $progressBar->start();

        Product::withoutGlobalScopes()->chunk($chunkSize, function($products, $index) use ($progressBar) {
            ScrapeProductsJob::dispatchNow($products);
            $progressBar->advance();
        });

        $progressBar->finish();
    }
}