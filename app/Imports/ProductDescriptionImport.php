<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductDescriptionImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	$updated = 0;

     	$collection->each(function($row, $index) use (&$updated) {

     		if ($index === 0) {
     			return true;
     		}

     		try {
	     		$product = Product::where('sku', trim($row[0]))->firstOrFail();
	     		$product->short_description = null;
                $product->description = trim($row[3]);
	     		$product->save();
	     		$updated++;
     		} catch (\Exception $e) {
     			dump($e->getMessage());
     		}

     	});

        dump($updated);
    }
}
