<?php

namespace App\Exports;

use App\Product;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ScrapedDataExport implements FromCollection, Responsable, WithHeadings
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
    		'product_id',
    		'sku',
    		'vendor_code',
    		'RS_price',
    		'FSD_price',
            'scrap match'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rows = collect();

        Product::whereNotNull('scraped_data')->where('scraped_data', '!=', '')->chunk(100, function($products) use (&$rows) {
        	foreach ($products as $product) {
        		foreach (Arr::wrap($product->scraped_data) as $row) {
	        		$rows->push([
	        			$product->id,
	        			$product->sku,
	        			$product->vendor_code,
	        			$product->price,
	        			$row['price'],
                        isset($row['scrap_column']) ? $row['scrap_column'] : $row['scrape_column'] ?? '',
	        		]);
	        	}
        	}
        });

        return $rows;
    }
}
