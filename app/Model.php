<?php 

namespace App;

use \DateTimeInterface;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * The Easy way to use cachable queries
 */
abstract class Model extends Eloquent
{
    use Rememberable;
    
    /**
     * Default models cache life time
     * 
     * @return int
     */
    public static function getDefaultCacheTime()
    {
        return config('default-variables.cache_life_time');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

