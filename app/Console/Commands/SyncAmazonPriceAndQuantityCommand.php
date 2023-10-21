<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;
use FME\Amazon\AmazonRepository;

class SyncAmazonPriceAndQuantityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fme:sync-amazon-price-and-quantity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Amazon products price and quantity';

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
        $reject = function ($item) {
            if (! is_array($item)) {
                return true;
            }

            if (count($item) === 0) {
                return true;
            }

            return false;
        };

        $amazon = new AmazonRepository();

        Product::fba()->chunk(20, function ($products) use ($amazon, $reject) {

            $skus = $products->pluck('sku')->toArray();

            collect(
                $amazon->getClient()->GetMyPriceForSKU($skus)
            )
            ->reject($reject)
            ->each(function ($item) use ($products) {
                $product = $products->where('sku', $item['SellerSKU'])->first();
                $product->price = $item['BuyingPrice']['ListingPrice']['Amount'];
                $product->save();
            });

            sleep(mt_rand(2, 5));

            collect(
                $amazon->getClient()->ListInventorySupply($skus)
            )
            ->reject($reject)
            ->each(function ($item) use ($products) {
                $product = $products->where('sku', $item['SellerSKU'])->first();
                $product->quantity = $item['TotalSupplyQuantity'];
                $product->save();
            });

            sleep(mt_rand(60, 61));
        });
    }
}
