<?php

use App\Imports\CategoryImport;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	(new CategoryImport)->import(
    		storage_path('app/public/imports/categoriesRebel.csv'), 
    		null, 
    		\Maatwebsite\Excel\Excel::CSV
    	);
    }
}
