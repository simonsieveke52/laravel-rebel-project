<?php 

namespace App\Http\View\Composers;

use Spatie\Tags\Tag;
use App\Category;
use App\Brand;
use App\Product;
use Illuminate\Contracts\View\View;

class NavigationComposer
{
    /**
     * @var categories collection
     */
    protected $navigationCategories;
 
    /**
     * Create a new categories composer.
     *
     * @param  Category $category
     * @param  Brand $category
     * @return void
     */
    public function __construct()
    {
        if (! $this->navigationCategories) {
            $this->navigationCategories = Category::onNavbar()
                ->whereNull('parent_id')
                ->withDepth()
                ->get();
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'categories' => $this->navigationCategories,
        ]);
    }
}