<?php

use Illuminate\Database\Seeder;
use App\Imports\RefreshedCategoriesImport;

class RefreshedCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new RefreshedCategoriesImport)->import(
            storage_path('app/public/imports/New Website Categories 6.30.21.csv'), 
            null, 
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
