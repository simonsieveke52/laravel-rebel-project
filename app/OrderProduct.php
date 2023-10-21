<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    /**
     * @var boolean
     */
    public $incrementing = true;

    /**
     * @return belongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return belongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
