<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;


class SkuToDotImport implements ToCollection
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $notfound = collect();

        $collection->each(function($row, $index) use (&$notfound) {

            if ($row[0] === null || $index <= 1) {
                return;
			}
			
            try {
                $product = Product::where('sku', $row[0])->firstOrFail();
                $product->vendor_code = $row[1];
                $product->save();
            } catch (\Exception $e) {
                $notfound->push($row);
            }
        });
    }
}