<?php

namespace App\Imports;

use App\Brand;
use App\Product;
use App\Category;
use App\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ImportMissingProducts implements ToCollection, WithCustomCsvSettings
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row, $index) {

        	if ($index == 0) {
        		return true;
        	}

        	try {
	        	$brand = Brand::firstOrCreate([
	        		'slug' => Str::slug($row[10]),
	        		'name' => $row[10]
	        	]);
	        	$brandId = $brand->id;
        	} catch (\Exception $e) {
        		$brandId = null;
        	}

        	try {
        		$product = Product::where('sku', $row[0])->firstOrFail();
        		$product->name = trim($row[1]);
        		$product->brand_id = $brandId;
        		$product->description = $row[2];
        		$product->price = floatval(str_replace(['USD', ' '], '', $row[5]));
        		$product->quantity = 10;
        		$product->status = true;
        		$product->upc = $row[8];
        		$product->mpn = $row[9];
        		$product->google_product_category = $row[11];
        		$product->weight = $row[12];
        		$product->frozen = $row[21] == 'Frozen';

                $product->is_fba = false;

        		$product->save();
        	} catch (\Exception $e) {
		        try {
		        	$product = new Product();
		        	$product->sku = trim($row[0]);
		        	$product->name = trim($row[1]);
		        	$product->slug = Str::slug(trim($row[1]) . '-' . $row[0]);
	        		$product->description = trim($row[2]);
	        		$product->price = floatval(str_replace(['USD', ' '], '', $row[5]));
	        		$product->brand_id = $brandId;
	        		$product->quantity = 20;
	        		$product->status = true;
	        		$product->upc = $row[8];
	        		$product->mpn = $row[9];
	        		$product->google_product_category = $row[11];
	        		$product->weight = floatval($row[12]);
	        		$product->frozen = $row[21] == 'Frozen';

                    $product->is_fba = false;
                    
	        		$product->save();
		        } catch (\Exception $e) {
		        	dump($e->getMessage());
		        }
        	}

            if (trim($product->sku) !== '') {
                
                if (Storage::disk('public')->exists("products/productImages/{$product->sku}__1.jpg")) {
                    echo "products/productImages/{$product->sku}__1.jpg" . PHP_EOL;
                    ProductImage::firstOrCreate([
                        'product_id' => $product->id,
                        'src' => $product->sku . '__1.jpg',
                    ]);
                }

                if (Storage::disk('public')->exists("products/productImages/{$product->sku}__2.jpg")) {
                    ProductImage::firstOrCreate([
                        'product_id' => $product->id,
                        'src' => $product->sku . '__2.jpg',
                    ]);
                }
            }

        	try {

        		if (trim($row[14]) != '') {
        			try {
	        			$category = Category::whereName($row[14])->orWhere('slug', Str::slug($row[14]))->firstOrFail();
		        		if (! in_array($category->id, $product->categories->pluck('id')->toArray())) {
			        		$product->categories()->attach($category);
		        		}
        			} catch (\Exception $e) {
        				
        			}
        		}

        		if (trim($row[15]) != '') {
        			try {
	        			$category = Category::whereName($row[15])->orWhere('slug', Str::slug($row[15]))->firstOrFail();
		        		if (! in_array($category->id, $product->categories->pluck('id')->toArray())) {
			        		$product->categories()->attach($category);
		        		}
        			} catch (\Exception $e) {
        				
        			}
        		}
        		
        		if (trim($row[16]) != '') {
        			try {
	        			$category = Category::whereName($row[16])->orWhere('slug', Str::slug($row[16]))->firstOrFail();
		        		if (! in_array($category->id, $product->categories->pluck('id')->toArray())) {
			        		$product->categories()->attach($category);
		        		}
        			} catch (\Exception $e) {
        				
        			}
        		}
        		
        		if (trim($row[17]) != '') {
        			try {
	        			$category = Category::whereName($row[17])->orWhere('slug', Str::slug($row[17]))->firstOrFail();
		        		if (! in_array($category->id, $product->categories->pluck('id')->toArray())) {
			        		$product->categories()->attach($category);
		        		}
        			} catch (\Exception $e) {
        				
        			}
        		}

	        	
        	} catch (\Exception $e) {
        		
        	}
        });
    }
    
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'Windows-1252'
        ];
    }
}
