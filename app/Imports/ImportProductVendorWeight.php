<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportProductVendorWeight implements ToCollection
{
	use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	$collection->each(function($row, $index) {
    		try {
	    		$product = Product::where('sku', $row[0])->withoutGlobalScopes()->update([
                    'vendor_weight' => $row[1]
                ]);
    		} catch (\Exception $e) {
    			dump($e->getMessage());
    		}
    	});
    }
}
