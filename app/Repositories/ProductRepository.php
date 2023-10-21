<?php

namespace App\Repositories;

use App\Product;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cookie;

class ProductRepository extends BaseRepository
{
    /**
     * Save cover
     *
     * @param UploadedFile $file
     *
     * @return false|string
     */
    public function saveCover(UploadedFile $file) 
    {
        return $file->store('products', ['disk' => 'public']);
    }

    /**
     * Save images
     *
     * @param Collection $collection
     * @param Product $product
     * @return void
     */
    public function saveProductImage(UploadedFile $file)
    {
        return $this->model->images()
            ->save(new ProductImage([
                'src' => $file->store('products', ['disk' => 'public']),
                'product_id' => $this->model->id
            ]));
    }


    /**
     * Get product with details
     */
    public function getProductWithDetails($product)
    {
        if(! $product instanceof Product) {
            try {
                $product = Product::where('slug', $product)->firstOrFail();
            } catch (\Exception $e) {
                try {
                    $product = Product::where('secondary_slug', $product)->firstOrFail();
                } catch (\Exception $e) {
                    $product = Product::where('id', $product)->firstOrFail();
                }
            }
        }
        
        // make this product visited
        if( !Cookie::has( $product->id ) ){
            // create cookie for this product as visited
            Cookie::queue( Cookie::make($product->id, 1, 60) );
            // mark product as visited
            $product->increment('clicks_counter');
        }

        $product->loadMissing(['images', 'categories', 'reviews', 'scrapped', 'nutrition', 'children', 'parent.children']);

        return $product;
    }

    /**
     * Get Related Products for a given product
     */
    public function getRelatedProducts($product)
    {
        $category = $product->categories->first();

        /**
         * Related Products
         * 
         * First, products in the same category
         * Second, products in the same parent category
         * Third, all products
         * 
         */

        $relatedProducts = $category->products;

        foreach($product->categories as $category)
        {
            $relatedProducts = $relatedProducts->merge($category->products);
                
                foreach($category->parent->children() as $siblingCategory) {
                    $relatedProducts = $relatedProducts->merge($siblingCategory->products);
                    $count += count($category->products);
                }
        }

        return $relatedProducts;
    }

    /**
     * filters a group of products by various request criteria in the request parameters
     * 
     * @param Request $request
     * @param Category $category
     * @param mixed $products
     * 
     * @return Collection $products
     * 
     */
    public function filterProducts(Request $request, $category = NULL, $products = NULL) {
        
        $products = $products ? $products : $category->products()->with(['images', 'brand'])->getResults();
        
        $chosenTypes = explode(',', $request->get('type'));
        $chosenProfessions = explode(',', $request->get('profession'));
        $chosenAvailability = $request->get('availability');
        $chosenPriceFrom = $request->get('price_from') == 0 ? NULL : $request->get('price_from') / 100;
        $chosenPriceTo = $request->get('price_to') == 0 ? NULL : $request->get('price_to') / 100;

        if($chosenPriceFrom !== NULL) {
            $products = $products->where('price','>=', $chosenPriceFrom);
        }

        if($chosenPriceTo !== NULL) {
            $products = $products->where('price','<=', $chosenPriceTo);
        }

        if($chosenAvailability === 'in-stock') {
            $products = $products->where('quantity','>', 0);
        }

        return $products;
    }

    /**
     * update products from boh file
     *
     * @param Product $product
     * @param array $row
     * 
     * @return
     */
    public function updateProductFromBoh(Product $product, array $row) : bool
    {
        $product->quantity = strtolower(trim($row[9])) === 'in stock' ? 20 : 0;

        $product->inventory_status = [
            [
                'warehouse' => 'illinois',
                'status' => strtolower($row[9] ?? ''),
            ],
            [
                'warehouse' => 'maryland',
                'status' => strtolower($row[10] ?? ''),
            ],
            [
                'warehouse' => 'modesto',
                'status' => strtolower($row[11] ?? ''),
            ],
            [
                'warehouse' => 'oklahoma',
                'status' => strtolower($row[12] ?? ''),
            ],
            [
                'warehouse' => 'burley',
                'status' => strtolower($row[13] ?? ''),
            ],
            [
                'warehouse' => 'arizona',
                'status' => strtolower($row[14] ?? ''),
            ],
        ];

        // $product->quantity = 20;

        // if (
        //     ( isset($row[67]) && strpos(strtolower(trim($row[67])), 'out of stock') !== false ) ||
        //     ( isset($row[6]) && strpos(strtolower(trim($row[6])), 'unavailable') !== false ) ||
        //     ( isset($row[6]) && strpos(strtolower(trim($row[6])), 'special-order') !== false ) ||
        //     ( isset($row[6]) && strpos(strtolower(trim($row[6])), 'not authorized') !== false ) ||
        //     ( isset($row[68]) && trim($row[68]) !== '' )
        // ) {
        //     $product->quantity = 0;
        // }

        // if (isset($row[61]) && $product->pack != $row[61]) {
        //     $product->pack = $row[61];
        // }

        // if (isset($row[62]) && $product->size != $row[62]) {
        //     $product->size = $row[62];
        // }

        // if (isset($row[62]) && $product->size_uom != $row[63]) {
        //     $product->size_uom = $row[63];
        // }

        // $product->inventory_status = [
        //     [
        //         'warehouse' => 'illinois',
        //         'status' => strtolower($row[67] ?? ''),

        //     ],
        //     [
        //         'warehouse' => 'maryland',
        //         'status' => strtolower($row[77] ?? ''),
        //     ],
        //     [
        //         'warehouse' => 'modesto',
        //         'status' => strtolower($row[87] ?? ''),
        //     ],
        //     [
        //         'warehouse' => 'oklahoma',
        //         'status' => strtolower($row[97] ?? ''),
        //     ],
        //     [
        //         'warehouse' => 'burley',
        //         'status' => strtolower($row[107] ?? ''),
        //     ],
        //     [
        //         'warehouse' => 'arizona',
        //         'status' => strtolower($row[117] ?? ''),
        //     ],
        // ];

        return $product->save();
    }
}


