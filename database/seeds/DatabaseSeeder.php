<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // GeneralFileSeeder::class,
            BrandsTableSeeder::class,
            CategoryTableSeeder::class,
            ProductTableSeeder::class,
            ProductRelationSeeder::class,
            ShippingTableSeeder::class,
            CountryTableSeeder::class,
            StateTableSeeder::class,
            ZipcodeTableSeeder::class,
            OrderStatusTableSeeder::class,
            UserListsTableSeeder::class,
            SkuToDotTableSeeder::class,
            ProductIgnoreTableSeeder::class,
            MarketingEmailTableSeeder::class,
            ZipcodeInventorySeeder::class,
            ProductVendorWeightSeeder::class,

            RefreshedCategoryTableSeeder::class,
            RefreshedProductsRelationSeeder::class,

            VoyagerProductChildrenSeeder::class,
            VoyagerDiscountSeeder::class,
        ]);
    }
}