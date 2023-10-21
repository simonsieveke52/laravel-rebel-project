<?php

namespace App\Imports;

use App\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class RefreshedCategoriesImport implements ToCollection
{
    use Importable;
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        \DB::table('category_product')->truncate();
        \DB::table('category_discount')->truncate();
        \DB::table('categories')->truncate();

        foreach ($collection as $index => $row) {

            if ($index === 0) {
                continue;
            }

            $parent = null;

            if (trim($row[0]) !== '') {
                try {
                    $parent = Category::withoutGlobalScopes()->whereName(trim($row[0]))->firstOrFail();
                } catch (\Exception $e) {
                    $parent = Category::create([
                        'slug' => \Str::slug(trim($row[0])),
                        'name' => trim($row[0]),
                        'on_navbar' => 1,
                    ]);
                }

                $parent->on_navbar = 1;
                $parent->save();
            }

            if (trim($row[1]) !== '') {
                try {
                    $parent = Category::withoutGlobalScopes()->whereName(trim($row[1]))->firstOrFail();
                } catch (\Exception $e) {
                    $parent = Category::create([
                        'slug' => \Str::slug(trim($row[1])),
                        'name' => trim($row[1]),
                        'parent_id' => $parent->id,
                        'on_navbar' => 1,
                    ]);
                }

                $parent->on_navbar = 1;
                $parent->save();
            }

            if (trim($row[2]) !== '') {
                try {
                    $parent = Category::withoutGlobalScopes()->whereName(trim($row[2]))->firstOrFail();
                } catch (\Exception $e) {
                    $parent = Category::create([
                        'slug' => \Str::slug(trim($row[2])),
                        'name' => trim($row[2]),
                        'parent_id' => $parent->id,
                        'on_navbar' => 1,
                    ]);
                }

                $parent->on_navbar = 1;
                $parent->save();
            }

            if (trim($row[3]) !== '') {
                try {
                    $parent = Category::withoutGlobalScopes()->whereName(trim($row[3]))->firstOrFail();
                } catch (\Exception $e) {
                    $parent = Category::create([
                        'slug' => \Str::slug(trim($row[3])),
                        'name' => trim($row[3]),
                        'parent_id' => $parent->id,
                        'on_navbar' => 1,
                    ]);
                }

                $parent->on_navbar = 1;
                $parent->save();
            }
        }

        Category::fixTree();

        Category::whereIn('slug', [
            'foods',
            'beverages',
            'equipment-supplies-disposables',
            'health-beauty-pet',
        ])
        ->withoutGlobalScopes()
        ->get()->each(function ($category) {
            
            $slug = $category->slug;

            if ($slug === 'equipment-supplies-disposables') {
                $slug = 'disposables';
            }

            if ($slug === 'health-beauty-pet') {
                $slug = 'janitorial-supplies';
            }

            $category->homepage_order = 1;
            $category->cover = 'storage/images/category/'.$slug.'.jpg';
            $category->save();
        });
    }
}
