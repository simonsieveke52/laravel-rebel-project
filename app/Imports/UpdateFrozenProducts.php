<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateFrozenProducts implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	$collection->each(function($row, $index) {

    		if ($row[0] === null || $index <= 1) {
                return;
			}

			try {
				$product = Product::where('sku', $row[0])->firstOrFail();
				$product->frozen = strtolower($row[21]) == 'frozen';
				$product->save();
			} catch (\Exception $e) {
				
			}
    	});
    }
}
