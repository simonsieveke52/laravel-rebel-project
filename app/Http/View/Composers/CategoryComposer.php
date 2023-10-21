<?php 

namespace App\Http\View\Composers;

use App\Category;
use Illuminate\Contracts\View\View;

class CategoryComposer
{
    /**
     * @var categories collection
     */
    protected $categories;

    /**
     * Create a new categories composer.
     *
     * @param  Category $category
     * @return void
     */
    public function __construct()
    {
        if (!$this->categories) {
            $this->categories = Category::onNavbar()->withDepth()->get()->toTree();
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
        $view->with('categories', $this->categories);
    }
}