<?php

namespace App\Imports;

use App\Product;
use App\ProductNutrition;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GeneralFileImport implements ToCollection, WithHeadingRow
{
	use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->each(function($row) {

        	$product = Product::where('id', $row['item_id'])
        		->withoutGlobalScopes()
        		->first();

            ProductNutrition::where('product_id', $product->id)->delete();

            $row = str_replace(['"Gram"', '"GRM"'], '"g"', json_encode($row));

        	ProductNutrition::create([
        		'content' => json_decode($row, true),
        		'product_id' => $product->id
        	]);
        });
    }
}
