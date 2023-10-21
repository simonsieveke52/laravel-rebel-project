<?php

use App\Shipping;
use Illuminate\Database\Seeder;

class ShippingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipping::create([
        	'name' => 'Regular items shipping',
			'base_cost' => 5.99,
            'is_frozen' => false,
        ]);

        Shipping::create([
            'name' => 'Frozen items shipping',
            'base_cost' => 29.99,
            'is_frozen' => true,
        ]);
    }
}
