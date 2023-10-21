<?php

/**
 * Auth.net config file.
 */
return [
	
	'production' => [
    	'login' => env('AUTHORIZE_PAYMENT_API_LOGIN_ID'),
	    'key' => env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'),
	],

    'local' => [
    	'login' => env('AUTHORIZE_PAYMENT_API_LOGIN_ID'),
	    'key' => env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'),
    ]
];



