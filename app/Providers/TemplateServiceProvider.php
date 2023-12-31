<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\CartComposer;
use App\Http\View\Composers\NavigationComposer;

/**
 * Class TemplateServiceProvider
 */
class TemplateServiceProvider extends ServiceProvider
{
	/**
     * Register the service provider.
     *
     * @return void
     */
	public function register()
	{
        $this->app->singleton(NavigationComposer::class);
	}

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View()->composer(
            ['front.categories.sidebar-category', 'front.shared.footer', 'front.shared.navigation-primary'], 
            NavigationComposer::class
        );
    }
}
