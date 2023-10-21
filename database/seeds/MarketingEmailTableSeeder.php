<?php

use Illuminate\Database\Seeder;
use App\Imports\MarketingEmailImport;

class MarketingEmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new MarketingEmailImport)->import(
    		storage_path('app/public/imports/marketing-list.csv'),
    		null, 
    		\Maatwebsite\Excel\Excel::CSV
    	);
    }
}
