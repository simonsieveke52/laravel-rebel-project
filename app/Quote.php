<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'quote_product')->withPivot('free_shipping','price','quantity','discount_amount','discount_value','discount_type');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteProduct::class);
    }

    public function emails()
    {
        return $this->hasMany(QuoteEmail::class)->orderByDesc('created_at');
    }

    public function getHasFlatRateShippingAttribute()
    {
        return $this->shipping_cost !== null;
    }
}
