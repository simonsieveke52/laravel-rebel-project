<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use App\Jobs\ScrapeProductsJob;
use App\Repositories\Unbxd\UnbxdRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductScrapeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_request_unbxd_endpoint()
    {
        $repo = new UnbxdRepository();

        $product = Product::find(13);

        ScrapeProductsJob::dispatchNow(
            Product::where('id', $product->id)->get()
        );

        $product->refresh();

        $this->assertTrue(count($product->scraped_data) > 0);
        $this->assertTrue($scrapedProduct->price > 0);
    }
}
