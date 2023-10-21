<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    protected $guarded = [];
    protected $table = 'quote_product';
    protected $casts = [
       'free_shipping' => 'boolean'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function get_discount_percent()
    {
        return round($this->discount_amount / ($this->product->price) * 100);
    }

    public function getTotalAttribute(){
        return ($this->price * $this->quantity) - $this->discount_amount;
    }
}
