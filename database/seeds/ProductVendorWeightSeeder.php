<?php

use Illuminate\Database\Seeder;
use App\Imports\ImportProductVendorWeight;

class ProductVendorWeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ImportProductVendorWeight)->import(
    		storage_path('app/public/imports/weights_.xlsx'),
    		null, 
    		\Maatwebsite\Excel\Excel::XLSX
    	);
    }
}
