<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use App\Category;

class DevPopualteHomeCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $popButtonStrings  = array("foods", "beverages", 
        "international-foods", "kitchen-equipment", "kitchen-smallware", "dinnerware", "disposables", "janitorial-supplies");
        DB::table('categories')->whereIn('slug', $popButtonStrings)->update(['homepage_order' => 1]);
    }
}
