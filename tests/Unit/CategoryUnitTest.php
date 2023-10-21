<?php

namespace Tests\Unit;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test get navbar categories
     * @return void
     */
    public function it_can_list_navbar_categories()
    {
    	factory(Category::class, 4)->create();

    	$categories = Category::onNavbar()->get();

        $this->assertTrue(
        	$categories->isNotEmpty()
        );
    }

    /**
     * @test get filter categories
     * @return void
     */
    public function it_can_list_filter_categories()
    {
    	factory(Category::class, 4)->create();
    	
    	$categories = Category::onFilter()->get();

        $this->assertTrue(
        	$categories->isNotEmpty()
        );
    }
}
