<?php

namespace App\Imports;

use App\Brand;
use App\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class BrandImport implements ToCollection
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
			
			$exitstingBrand = Brand::where('slug', Str::slug($row[10]))->first();
            
			if(!$exitstingBrand && $row[10] != null) {
				$brand = Brand::create([
                    'name' => ucwords($row[10]),
                    'slug' => Str::slug($row[10]),
				]);
			}
        });
    }
}
