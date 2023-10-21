<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\{ Product, ProductImage, Brand };
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
		$frozenValue = 0;
		$brand = null;
        $collection->each(function($row, $index) use (&$frozenValue, &$brand){

            if ($row[0] === null || $index <= 1) {
                return;
			}
			
			$exitstingProduct = Product::where('sku', $row[0])->first();
			if(!$exitstingProduct){
				// L x W x H
				$dimensions = explode('x', $row[13]);

				$length = $dimensions[0] ?? 0;
				$width = $dimensions[1] ?? 0;
				$height = $dimensions[2] ?? 0;
				
				if($row[13] == "Frozen/Refer"){
					$frozenValue = 1;
				}else{
					$frozenValue = 0;
				}
				$exitstingBrand = Brand::where('slug', Str::slug($row[10]))->first();
				if($exitstingBrand){
					$brand = $exitstingBrand->id;
				}

				$product = Product::create([
					'name' => $row[1],
					'slug' => Str::slug($row[1] . '-' . $row[0]),
					'sku' => $row[0],
					'price' => $row[5],
					'description' => $row[3],
					'frozen' => $frozenValue,
					'brand_id' => $brand,
					'mpn' => $row[9],
					'weight' => $row[12],
					'upc' => $row[8],

					'quantity' => 10,
				]);
				
				$imgSrc = substr(substr($row[7], strrpos($row[7], '/') + 1), 0);

				$productImage = ProductImage::create([
					'product_id' 	=> $product->id,
					'src'			=> $imgSrc,
					'is_main'		=> 1
				]);
				
				$productImage->product()->associate($product);
			}
        });
    }
}
