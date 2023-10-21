<?php

use Illuminate\Database\Seeder;
use App\Imports\ProductRelationImport;

class ProductRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new ProductRelationImport)->import(
    		storage_path('app/public/imports/rebelProductToCategory.csv'), 
    		null, 
    		\Maatwebsite\Excel\Excel::CSV
    	);
    }
}
