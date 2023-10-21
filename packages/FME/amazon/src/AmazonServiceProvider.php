<?php

namespace FME\Amazon;

use FME\Amazon\AmazonRepository;
use Illuminate\Support\ServiceProvider;

class AmazonServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/amazon-mws.php' => config_path('amazon-mws.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/amazon-mws.php', 'amazon-mws');

        $this->app->singleton('AmazonRepository', function() {
            return new AmazonRepository();
        });
    }
}
