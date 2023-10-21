<?php

use App\Imports\CategoryImport;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     *
     * @throws Exception
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
