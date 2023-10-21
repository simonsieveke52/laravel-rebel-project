<?php

namespace App;

use Illuminate\Support\Arr;

class ProductScrapped extends Model
{
    /**
     * @var array
     */
    protected $appends = [
        'bought_together'
    ];

	/**
	 * @var array
	 */
    protected $guarded = [];

	/**
	 * @var array
	 */
    protected $casts = [
		'widgets' => 'json',
    	'response' => 'json',
        'similar_products' => 'json',
		'recommended_products' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    /**
     * @return collection
     */
    public function getBoughtTogetherAttribute()
    {
        $skus = data_get(Arr::wrap($this->recommended_products), 'data.products.*.sku');

        return cache()->remember(md5('scrapped-' . implode(', ', $skus)), now()->addHour(), function () use ($skus) {
            return ProductScrapped::with('product')->whereIn('sku', $skus)
                ->get()
                ->map(function($scrapped) {
                    return $scrapped->product;
                })
                ->sortBy('sales_count');
        });
    }
}
