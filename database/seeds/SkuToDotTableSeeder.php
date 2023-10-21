<?php

use App\Imports\SkuToDotImport;
use Illuminate\Database\Seeder;

class SkuToDotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new SkuToDotImport)->import(
    		storage_path('app/public/imports/skus.xlsx'), 
    		null, 
    		\Maatwebsite\Excel\Excel::XLSX
    	);
    }
}
