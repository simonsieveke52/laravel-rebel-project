<?php

use Illuminate\Database\Seeder;
use App\Imports\ImportZipcodeInventoryList;

class ZipcodeInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ImportZipcodeInventoryList)->import(
    		storage_path('app/public/imports/FedEx_Ground_Shipping_Speeds_from_DOT_DC.xlsx'),
    		null, 
    		\Maatwebsite\Excel\Excel::XLSX
    	);
    }
}
