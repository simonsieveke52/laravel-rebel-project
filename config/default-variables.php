<?php 

/*
|--------------------------------------------------------------------------
| default global variables stored in this array
|--------------------------------------------------------------------------
|
*/

return [

	/**
	 * Default cache life time is 24h
	 */
	'cache_life_time' => 86400,

	'default-image' => 'notfound.jpg',

	'phone' => '8774680788',

	/**
	 * Shipping prices
	 */
	'frozen' => [
		'min' => 12.50,
		'per_pound' => 0.72,
		'fulfillment_fee' => 19.50,
	],
	
	'regular' => [
		'min' => 12.50,
		'per_pound' => 0.72,
		'fulfillment_fee' => 4.50,
	],

	/**
	 * Tax enabled
	 */
	'tax_status' => false
];