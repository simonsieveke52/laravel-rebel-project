<?php

use App\Brand;
use App\Product;
use Illuminate\Support\Str;
use App\Imports\BrandImport;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        try {
            (new BrandImport)->import(
        		storage_path('app/public/imports/rebelProduct.csv'), 
        		null, 
        		\Maatwebsite\Excel\Excel::CSV
        	);

            (new BrandImport)->import(
                storage_path('app/public/imports/upload_brand_descriptions_to_website_10.2.20.csv'), 
                null, 
                \Maatwebsite\Excel\Excel::CSV
            );

            // $rows = readCsvFile('upload_brand_descriptions_to_website_10.2.20.csv', 'app/public/imports/');

            // foreach ($rows as $index => $row) {
            //     if ($index === 0) {
            //         continue;
            //     }

            //     $brand = Brand::firstOrCreate(
            //         ['slug' => Str::slug($row[1])], 
            //         [
            //             'slug' => Str::slug($row[1]),
            //             'name' => ucfirst(strtolower($row[1])),
            //             'description' => utf8_encode(trim($row[2]))
            //         ]
            //     );

            //     $brand->slug = Str::slug($row[1]);
            //     $brand->name = ucfirst(strtolower($row[1]));
            //     $brand->description = utf8_encode(trim($row[2]));
            //     $brand->save();

            //     $product = Product::where('sku', $row[0])->withoutGlobalScopes()->update([
            //         'brand_id' => $brand->id,
            //     ]);
            // }

        } catch(Exception $e) {
            throw new Exception('Exception occur ' . $e);
        }
    }
}
