<?php

namespace App;

use App\{Model, Product};
use Illuminate\Support\Str;
use App\Scopes\EnabledScope;
use App\Scopes\CachableScope;


class UserList extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];




    /**
     * Related products
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'product_id');
    }

 


}
