<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FrozenCartFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'frozen_cart';
    }
}