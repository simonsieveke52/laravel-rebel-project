<?php

namespace FME\Amazon;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FME\Amazon\AmazonRepository
 */
class AmazonFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AmazonRepository';
    }
}
