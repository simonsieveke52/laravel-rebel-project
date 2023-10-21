<?php

use Illuminate\Database\Seeder;
use App\Imports\RefreshedProductsImport;

class RefreshedProductsRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new RefreshedProductsImport)->import(
            storage_path('app/public/imports/New Website Category Item Assignments 6.30.21.csv'), 
            null, 
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
