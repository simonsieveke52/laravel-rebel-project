<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;
use App\Repositories\Unbxd\UnbxdRepository;

class ExportScrappedDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:export-scraped-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and export data to csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = storage_path('app/public/csv/scrapped-' . strtotime('now') . '.csv');
        $this->info('Create file ' . $fileName);

        $progressBar = $this->output->createProgressBar(Product::withoutGlobalScopes()->count());

        $progressBar->start();

        $repository = new UnbxdRepository();

        $fp = fopen($fileName, 'w');

        fputcsv($fp, [
            'product_id', 'product_sku', 'vendor_code', 'current_price','timestamp','price','supplier_website','productUrl','url_key','gtin','upc','sku','scrap_column'
        ]);

        Product::withoutGlobalScopes()->chunk(100, function($products) use ($repository, $fp, $progressBar) {

            $products->each(function($product) use ($repository, $fp, $progressBar) {
                try {
                    $response = $repository->getScrapedData($product);
                    $progressBar->advance();

                    if (count($response) === 0) {
                        return true;
                    }

                    fputcsv($fp, array_merge([
                        $product->id,
                        $product->sku,
                        $product->vendor_code
                    ], array_values($response)));

                } catch (\Exception $exception) {
                    logger($exception->getMessage());
                }
            });
        });

        fclose($fp);

        $this->info('File generated ' . $fileName);
    }
}
