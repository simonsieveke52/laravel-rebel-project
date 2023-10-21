<?php

namespace FME\GoogleAdwords;

use Google\AdsApi\Common\Configuration;
use Illuminate\Support\ServiceProvider;
use FME\GoogleAdwords\AdWordsRepository;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;

class GoogleAdwordsServiceProvider extends ServiceProvider
{
	/**
	 * Boot the service provider
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/../config/google-adwords.php' => config_path('google-adwords.php'),
        ], 'config');
	}

	/**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/google-adwords.php', 'google-adwords');

        $this->app->singleton('AdWordsRepository', function ($app) {
            return new AdWordsRepository();
        });
    }
}
