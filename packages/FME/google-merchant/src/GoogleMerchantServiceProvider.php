<?php

namespace FME\GoogleMerchant;

use FME\GoogleMerchant\GoogleProducts;
use Illuminate\Support\ServiceProvider;
use FME\GoogleMerchant\OrderMerchantService;
use FME\GoogleMerchant\Repositories\AdWordsRepository;
use FME\GoogleMerchant\Contract\GoogleMerchantContract;

class GoogleMerchantServiceProvider extends ServiceProvider
{
	/**
	 * Boot the service provider
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/../config/google-api.php' => config_path('google-api.php'),
            __DIR__.'/../config/google-json-config/merchant-info.json' => config_path('google-json-config/merchant-info.json'),
            __DIR__.'/../config/google-json-config/service-account.json' => config_path('google-json-config/service-account.json')
        ], 'config');
	}

	/**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/google-api.php', 'google-api');
        
        $this->app->singleton('Google_Client', function ($app) {
            return new \Google_Client();
        });

        $this->app->singleton('ContentSession', function ($app) {
            return new ContentSession();
        });

        $this->app->singleton('GoogleProducts', function ($app) {
            return new GoogleProducts();
        });
        
        $this->app->singleton('OrderMerchantService', function ($app) {
            return new OrderMerchantService();
        });
        
        $this->app->singleton('GoogleMerchantContract', function ($app) {
            return new GoogleMerchantContract();
        });
    }
}
