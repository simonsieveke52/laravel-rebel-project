<?php

namespace App\Imports;

use App\Product;
use App\Category;
use App\ProductImage;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductRelationImport implements ToCollection
{
	use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row, $index) use (&$rawKeys, &$rawKeyValues) {

        	if ($index == 0) {
        		return true;
        	}

        	try {
	 			$product = Product::where('sku', $row[1])->firstOrFail();
				$category = Category::where('name', $row[3])->firstOrFail();
				$product->categories()->attach($category);
        	} catch (\Exception $e) {
        		dump($e->getMessage());
        	}
		});
    }
}