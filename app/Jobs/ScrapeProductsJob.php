<?php

namespace App\Jobs;

use App\ProductScrapped;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\Unbxd\UnbxdRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ScrapeProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed
     */
    protected $products;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $products)
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repository = new UnbxdRepository();

        $this->products->each(function($product) use ($repository) {

            try {

                sleep(1);

                $response = $repository->getScrapedData($product);

                if (count($response) === 0) {
                    return true;
                }

                try {
                    $scrape = ProductScrapped::where('product_id', $product->id)->firstOrFail();
                    $scrape->update($response);
                } catch (\Exception $e) {
                    ProductScrapped::create($response);
                }

            } catch (\Exception $exception) {
                logger($exception->getMessage());
            }
        });
    }
}
