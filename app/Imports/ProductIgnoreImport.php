<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;


class ProductIgnoreImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row, $index){

            if ($row[0] === null || $index <= 1) {
                return;
			}
			
            try {
    			$product = Product::where('sku', $row[0])->firstOrFail();
                $product->status = false;
                $product->save();
            } catch (\Exception $e) {
                
            }
        });
    }
}