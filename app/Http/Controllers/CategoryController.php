<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryProduct;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;

class CategoryController extends Controller
{   
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @param ProductRepository $productRepo
     */
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        $parentCategories = Category::whereStatus(true)
            ->remember(config('default-variables.cache_life_time'))
            ->ancestorsAndSelf($category);

        $parentCategoriesIds = $parentCategories->pluck('id')->all();

        if (! $request->ajax()) {
            return view('front.categories.list', [
                'parentCategoriesIds' => $parentCategoriesIds,
                'category' => $category,
            ]);
        }
        
        $query = $category->products()
            ->with(['images', 'brand', 'children'])
            ->where('quantity', '>', 0)
            ->when(intval($request->input('priceFrom')) !== 0, function($query) use ($request) {
                return $query->where('price', '>', floatval($request->input('priceFrom')));
            })
            ->when(intval($request->input('priceTo')) !== 0, function($query) use ($request) {
                return $query->where('price', '<', floatval($request->input('priceTo')));
            });
    
        switch ($request->get('sortBy', 'relevance')) {

            case 'l-t-h':
                $query = $query->orderBy('price', 'asc');
                break;

            case 'h-t-l':
                $query = $query->orderBy('price', 'desc');
                break;
            
            default:
                $query = $query->orderBy('sales_count', 'desc');
                break;
        }

        $products = $query->paginate(
            $request->get('perPage', 24)
        );

        $categoryChilds = $category->children->map(function ($item) {
            $item->depth += 1;
            return $item->only(['slug', 'name', 'depth']);
        });

        return response()->json([
            'parentCategories' => $parentCategories,
            'navItems' => $categoryChilds,
            'category' => $category,
            'products' => $products,
            'maxPrice' => 0,
            'minPrice' => 0,
        ]);
    }

}

