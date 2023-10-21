<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsMissingImagesExport implements FromCollection, Responsable
{
	use Exportable;

    /**
     * @var string
     */
    private $writerType = Excel::CSV;

    /**
     * @return array
     */
    public function headings(): array
    {
    	return [
            'SKU'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $csv = readCsvFile('Add New Dot Items to Website 9.29.20.csv', 'app/public/imports/');

        $skus = [];

        foreach ($csv as $index => $row) {
        	if ($index === 0) {
        		continue;
        	}

        	$skus[] = $row[0];
        }

        $response = collect();

        // (new \App\Exports\ProductsMissingImagesExport())->store('missing-images.csv', 'public-csv')

        foreach ($skus as $sku) {
            try {
            	$product = Product::where('sku', $sku)->withoutGlobalScopes()->firstOrFail();

            	if (strpos($product->main_image, 'storage/notfound') !== false) {
            		$response->push([$sku]);
            	}
            } catch (\Exception $e) {
            }
        }

        return $response->unique();
    }
}
