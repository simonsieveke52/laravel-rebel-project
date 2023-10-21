<?php

namespace App\Imports;

use App\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CategoryImport implements ToCollection
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $total = 0;
        $errors = 0;
        $parent = 'parent';
        $child = 'child';

        // BEGIN Creating the main nav bar top items as categories from Col 0 in csv**************
        $rows->each(function ($row, $index) use (&$total, &$errors, &$parent, &$child) {
            $flag = false;
            if ($index < 1) {
                return;
            }
            if($index >= 1){
                try {
                    if ($row[1] != null){
                        $child = $row[1];
                    
                        if($row[2] != null){
                            $parent = Category::where('name', $row[2])->first(); 
                            $flag = true;
                        }
                    }
                    if($flag){
                        Category::create([
                            'slug' => $parent->name."_".Str::slug($child),
                            'name' => $child,
                            'parent_id' => $parent->id,
                            'on_navbar' => 1,
                        ]);
                    }else{
                        Category::create([
                            'slug'                => Str::slug($child),
                            'name'                => $child,
                            'on_navbar'           => 1,
                        ]);
                    }
                            $total++;
                } catch (\Exception $e) {
                    $errors++;
                    logger(Str::limit($e->getMessage(), 150));
                }
            }
        });

        $popButtonStrings  = array(
            "foods",
            "beverages", 
            "international-foods",
            "kitchen-equipment",
            "kitchen-smallware",
            "dinnerware",
            "disposables",
            "janitorial-supplies"
        );
        
        for($i = 0; $i < count($popButtonStrings); $i++) {
            $category = Category::where('slug', $popButtonStrings[$i])->first();
            $category->homepage_order = $i + 1;
            $category->save();
        }

        // END Creating the main nav bar top items as categories from Col 0 in csv**************
        logger($total);
        logger($errors);
        logger($rows->count());
    }
}