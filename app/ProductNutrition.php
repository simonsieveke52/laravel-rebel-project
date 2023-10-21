<?php

namespace App;

class ProductNutrition extends Model
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'product_id', 'content'
	];

	/**
	 * @var array
	 */
    protected $casts = [
    	'content' => 'json',
    ];
}
