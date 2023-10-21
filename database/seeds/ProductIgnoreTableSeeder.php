<?php

use App\Imports\ProductIgnoreImport;
use Illuminate\Database\Seeder;

class ProductIgnoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new ProductIgnoreImport)->import(
    		storage_path('app/public/imports/rebelProductIgnore.csv'), 
    		null, 
    		\Maatwebsite\Excel\Excel::CSV
    	);
    }
}