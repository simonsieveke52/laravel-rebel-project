<?php

namespace App\Imports;

use App\Product;
use App\Category;
use App\CategoryProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class RefreshedProductsImport implements ToCollection
{
    use Importable;
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $row) {

            if ($index === 0) {
                continue;
            }

            try {
                $product = Product::withoutGlobalScopes()->where('sku', $row[0])->firstOrFail();

                $product->categories()->withoutGlobalScopes()->detach();

                CategoryProduct::where('product_id', $product->id)
                    ->withoutGlobalScopes()
                    ->delete();

                $ids = [];

                if (trim($row[1]) !== '') {
                    try {
                        $category = Category::withoutGlobalScopes()->where('name', trim($row[1]))->firstOrFail();
                        $ids[] = $category->id;
                    } catch (\Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }

                if (trim($row[2]) !== '') {
                    try {
                        $category = Category::withoutGlobalScopes()->where('name', trim($row[2]))->firstOrFail();
                        $ids[] = $category->id;
                    } catch (\Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }

                if (trim($row[3]) !== '') {
                    try {
                        $category = Category::withoutGlobalScopes()->where('name', trim($row[3]))->firstOrFail();
                        $ids[] = $category->id;
                    } catch (\Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }

                if (trim($row[4]) !== '') {
                    try {
                        $category = Category::withoutGlobalScopes()->where('name', trim($row[4]))->firstOrFail();
                        $ids[] = $category->id;
                    } catch (\Exception $e) {
                        echo $e->getMessage() . PHP_EOL;
                    }
                }

                if (count($ids)) {
                    $product->categories()->withoutGlobalScopes()->attach(
                        array_unique($ids)
                    );
                }

            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}
