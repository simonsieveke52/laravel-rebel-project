<?php

use Illuminate\Database\Seeder;
use App\Imports\GeneralFileImport;

class GeneralFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new GeneralFileImport)->import(
    		storage_path('app/public/imports/nutrittions-attributes.xlsx'), 
    		null, 
    		\Maatwebsite\Excel\Excel::XLSX
    	);
    }
}
