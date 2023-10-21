<?php

use App\Imports\ProductImport;
use Illuminate\Database\Seeder;
use App\Imports\ImportNewProducts;
use App\Imports\ProductPricingImport;
use App\Imports\UpdateFrozenProducts;
use App\Imports\ImportMissingProducts;
use App\Imports\ProductDescriptionImport;
use App\Imports\UpdateProductsSecondarySlug;
use App\Imports\UpdateImportedProductsImport;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     //    (new ImportNewProducts)->import(
    	// 	storage_path('app/public/imports/SuitecommerceFieldsResults-Table1.csv'), 
    	// 	null, 
    	// 	\Maatwebsite\Excel\Excel::CSV
    	// );

        // Add New Dot Items to the Website 3.9.21.zip
        // Add New Dot Items to Website 3.9.21.csv
        // Add New Dot Items to Website 3.9.21 Additional Fields.csv

        (new ImportMissingProducts)->import(
         // storage_path('app/public/imports/Google_Merchant_Center_feed_Initial_Rebel_Smuggling_missing_7k_items.csv'), 
            storage_path('app/public/imports/Add New Dot Items to Website 3.9.21.csv'), 
            null,
            \Maatwebsite\Excel\Excel::CSV
        );

        (new UpdateImportedProductsImport)->import(
            storage_path('app/public/imports/Add New Dot Items to Website 3.9.21 Additional Fields.csv'), 
            null,
            \Maatwebsite\Excel\Excel::CSV
        );


        // (new UpdateFrozenProducts)->import(
        //  storage_path('app/public/imports/Google Merchant Center feed - Initial - Rebel Smuggling - Sept 12 Version (2).csv'), 
        //  null, 
        //  \Maatwebsite\Excel\Excel::CSV
        // );

        // (new UpdateProductsSecondarySlug)->import(
        //     storage_path('app/public/imports/rebelProduct.csv'), 
        //     null, 
        //     \Maatwebsite\Excel\Excel::CSV
        // );

        // (new ProductImport)->import(
        //     storage_path('app/public/imports/rebelProduct.csv'), 
        //     null, 
        //     \Maatwebsite\Excel\Excel::CSV
        // );

        // (new ProductPricingImport)->import(
        //     storage_path('app/public/imports/pricing.xlsx'), 
        //     null, 
        //     \Maatwebsite\Excel\Excel::XLSX
        // );

        // (new ProductDescriptionImport)->import(
        //     storage_path('app/public/imports/products-details.csv'), 
        //     null, 
        //     \Maatwebsite\Excel\Excel::CSV
        // );
    }
}
