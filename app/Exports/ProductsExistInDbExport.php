<?php

namespace App\Exports;

use App\Product;
use App\UserFile;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExistInDbExport implements FromCollection, Responsable
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
            'SKU',
            'Product ID',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $csv = UserFile::where('file_type', 'boh')
        	->orderBy('id', 'desc')
        	->first()
        	->content;

        $rows = collect();

        foreach ($csv as $row) {
            try {
            	Product::where('vendor_code', $row[11])->firstOrFail();
            } catch (\Exception $e) {
            	$rows->push($row);
            }
        }

        return $rows;
    }
}
