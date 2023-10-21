<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateProductsSecondarySlug implements ToCollection
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
				$secondarySlug = str_replace(
					['http://www.rebelsmuggling.com/', 'https://www.rebelsmuggling.com/', 'http://rebelsmuggling.com/', 'https://rebelsmuggling.com/'], 
					'', 
					$row[3]
				);

				$product->secondary_slug = $secondarySlug;
				
				if (trim($row[11]) !== '') {
					$product->google_product_category = $row[11];
				}

				$product->save();

			} catch (\Exception $e) {
				
			}
    	});
    }
}
