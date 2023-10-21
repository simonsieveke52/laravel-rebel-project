<?php

namespace Tests\Feature;

use App\Order;
use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShippingCodeUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_load_inventory()
    {
        $product = Product::first();

        // $content = readCsvFile('I0155250.CSV');

        // foreach ($content as $index => $row) {

        //     if ($index === 0) {
        //         continue;
        //     }

        //     $inventory = [
        //         [
        //             'warehouse' => 'maryland',
        //             'status' => strtolower($row[77] ?? ''),
        //         ],
        //         [
        //             'warehouse' => 'modesto',
        //             'status' => 'in stock',
        //         ],
        //         [
        //             'warehouse' => 'oklahoma',
        //             'status' => strtolower($row[97] ?? ''),
        //         ],
        //         [
        //             'warehouse' => 'burley',
        //             'status' => strtolower($row[107] ?? ''),
        //         ],
        //         [
        //             'warehouse' => 'arizona',
        //             'status' => 'in stock',
        //         ],
        //     ];

        //     $product->inventory_status = $inventory;
        //     $product->save();
        // }

        $order = Order::find(12787);

        $zipcode = $order->shippingAddress->zipcode;

        $code = $product->getShippingCodeAttribute($zipcode);

        $this->assertTrue($code === 'F11');

        // 77 => "MARYLAND STOCK STATUS"
        // 87 => "MODESTO STOCK STATUS"
        // 97 => "OKLAHOMA STOCK STATUS"
        // 107 => "BURLEY STOCK STATUS"
        // 117 => "ARIZONA STOCK STATUS"
    }
}
