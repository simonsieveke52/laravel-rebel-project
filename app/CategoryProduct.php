<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryProduct extends Pivot
{
    /**
     * Related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->HasMany(Product::class, 'id', 'product_id')
	        ->remember(config('default-variables.cache_life_time'));
    }
}