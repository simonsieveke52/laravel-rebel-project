<?php

namespace App\Http\Controllers;

use App\Review;
use App\Product;
use App\Category;
use App\Rules\IsEmpty;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\CartItem;
use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Cookie;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @var ReviewRepository
     */
    protected $reviewRepo;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->reviewRepo = new ReviewRepository(new Review());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        if(trim($request->input('keyword')) === '') {
            return redirect()->back();
        }

        $query = Product::when(intval($request->input('priceFrom')) !== 0, function($query) use ($request) {
                return $query->where('price', '>', floatval($request->input('priceFrom')));
            })
            ->when(intval($request->input('priceTo')) !== 0, function($query) use ($request) {
                return $query->where('price', '<', floatval($request->input('priceTo')));
            })
            ->with(['images', 'brand'])
            ->where('quantity', '>', 0);

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

        $query = ! ($request->has('ids') && is_array($request->input('ids')))
            ? $query->search($request->input('keyword'))
            : $query->whereIn('id', $request->input('ids'));

        $products = $query->paginate($request->get('perPage', 24));

        try {
            $category = $products->first()->categories->first();
            $categoryChilds = $category->children->map(function ($item) {
                $item->depth += 1;
                return $item->only(['slug', 'name', 'depth']);
            });
        } catch (\Exception $e) {
            $category = null;
        }

        if ($category) {
            $parentCategories = Category::remember(config('default-variables.cache_life_time'))
                ->ancestorsAndSelf($category);

            $parentCategoriesIds = $parentCategories->pluck('id')->all();
        }

        if (! $request->ajax()) {
            return view('front.categories.list', [
                'parentCategoriesIds' => $parentCategoriesIds ?? [],
                'category' => $category,
            ]);
        }

        $maxPrice = floatval($products->max('price'));
        $minPrice = floatval($products->min('price'));

        return response()->json([
            'parentCategories' => $parentCategories ?? [],
            'navItems' => $categoryChilds ?? [],
            'products' => $products,
            'category' => $category,
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice,
        ]);
    }

    public function quickOrder(Request $request)
    {
        if(!$request->keyword) {
            return view('front.categories.list', [
                'paginationCount' => 24,
                'viewType'  => 'grid',
                'sortBy'    => 'relevance',
                'products'  => collect(),
                'maxPrice'  => NULL,
                'search'    => ''
            ]);
        }
        $products = Product::search($request->keyword)->get();
        $totalProducts = count($products);
        if(count($products) > 0) {
            $products = $this->productRepo->filterProducts($request, NULL, $products);
        }
        $maxPrice = $products->max('price') ?? NULL;

        $viewType = $request->get('view_type') ?? 'grid';
        $paginationCount = $request->get('pagination_count') ? intval($request->get('pagination_count')) : 24;
        $sortBy = $request->get('sort_by') ?? 'relevance';

        if(count($products) > 0) {
            $products = $this->productRepo->filterProducts($request, NULL, $products)->paginate($paginationCount);
        }

        $search = TRUE;

        return view('front.categories.list',
            compact(
                'paginationCount',
                'viewType',
                'sortBy',
                'products',
                'maxPrice',
                'search',
                'totalProducts'
            )
        );
    }

    /**
     * Get the product
     *
     * @param Mixed $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $product)
    {
        $product = $this->productRepo->getProductWithDetails($product);

        $category = $product->category;
        $parentCategories = Category::ancestorsAndSelf($category);
        $parentCategoriesIds = $parentCategories->pluck('id')->all();

        $parents = $parentCategories->pluck('id');
        $parentCategoriesIds = $parents->merge($parentCategoriesIds)->unique()->all();

        $boughtTogether = collect(
                $product->append('bought_together')->bought_together
            )
            ->reject(function($product) {
                return ! $product instanceof Product;
            })
            ->reject(function($product) {
                return ! is_object($product);
            })
            ->take(4)
            ->values();

        return view('front.products.show', [
            'parentCategories' => $parentCategories,
            'parentCategoriesIds' => $parentCategoriesIds,
            'category' => $category,
            'reviews' => $product->reviews,
            'boughtTogether' => $boughtTogether,
            'product' => $product,
            'parent' => $product->parent,
            'children' => $product->children,
        ]);
    }

    /**
     * Related Products View
     * TODO, give more arguments to change what algorithm to use for related products
     *
     * @param Mixed $product
     *
     * @return \Illuminate\View\View|string
     */
    public function relatedProducts($product)
    {
        $product = $this->productRepo->getProductWithDetails($product);

        $relatedProducts = $this->productRepo->getRelatedProducts($product);

        if(count($relatedProducts) == 0){
            return '';
        }

        return view('front.products.related', compact('relatedProducts'));
    }

    /**
     * Product Quick View
     * TODO, give more arguments to change what algorithm to use for related products
     *
     * @param Mixed $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function quickView($product)
    {
        $product = $this->productRepo->getProductWithDetails($product);

        return view('front.products.quick-view', compact('product'));
    }

    /**
     * Add a review to a product
     *
     */

     public function review(Request $request, Product $product){
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'grade' => 'required',
            'title' => 'required',
            'email_address' => 'required|email:rfc,dns|max:191',
            'review_dont_fill' => [new IsEmpty],
        ]);

        $data = $request->except('_token');
        $data['grade'] = (float) $request['grade'];

        $review = $this->reviewRepo->createReview($data);

        return $review;
     }
}
