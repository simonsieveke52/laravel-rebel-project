<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateImportedProductsImport implements ToCollection
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
        		$product = Product::where('sku', $row[0])->firstOrFail();
        		$product->vendor_code = $row[1];
        		$product->cost = $row[3];
        		$product->save();
        	} catch (\Exception $e) {
        		
        	}

        });
    }
}
