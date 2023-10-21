<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportNewProducts implements ToCollection
{
	use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row) {

        	dd($row);

        	try {
        		Product::where('sku', $row[0])->firstOrFail();
        	} catch (\Exception $e) {
		        try {
		        	Product::create([
			        	'sku' => $row[0],
			        	'quantity' => 12,
			        	'status' => true,
			        	'vendor_code' => $row[1],
			        	'name' => $row[2],
			        	'description' => $row[3],
			        	'secondary_slug' => $row[4],
			        	'price' => $row[5],
			        	'weight' => $row[6],
			        	'frozen' => trim($row[7]) === '' ? 0 : 1,
			        	'slug' => Str::slug($row[2].'-'.$row[0]),
			        ]);
		        } catch (\Exception $e) {
		        	dump($e->getMessage());
		        }
        	}
        });
    }
}
