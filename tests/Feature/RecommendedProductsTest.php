<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use Tests\TestCase;
use App\ProductScrapped;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecommendedProductsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $product = Product::find(22);

        $this->assertTrue(
            ! collect($product->append('bought_together')->bought_together)->take(4)->values()->isEmpty()
        );
    }
}
