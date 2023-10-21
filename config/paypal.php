<?php

/**
 * Here all configuration required in Paypal payments
 * For the mode, use eather live or sandbox
 * 
 */
return [

	/**
	 * Sandbox creds
	 * 
	 */
	'local' => [
	    'account_id' => 'ecommerce@fairmontsupply.com',
	    'client_id' => 'AWnkp81DINMcYK2e9k04Xsr8OPph5UY-aY4Gk3if0cUSaKRPvCigODKBuJ9pJO_c9tZ9v0lg0cjZXvOV',
	    'client_secret' => 'EBs7gE7_7LVw4Q7TVHB_vllj6Y3YXOpgDCXJ9px2F0Fkt4NXz9AD9LIeOAiD3bJlXMmBHnAmRS1YAIfQ',
	    'mode' => 'sandbox'
	],

	/**
	 * Production creds
	 * 
	 */
	'production' => [
		'account_id' => 'fsebay@fairmontsupply.com',
	    'client_id' => 'AQdmPp8frYRYIrNpJlP_j0Ef2JijrAfUaTm9-6rbUpDoIMIJFVIyjzftSsIWYz-zKlFHC-6k-0HZSSvi',
	    'client_secret' => 'EK2Ti00DRf65ukIUI0l5PCuxaD67lZ-LkHY7Bx3y32kYirnbGqWYgZOhHu6LYMwjbIyEAzcOU_-Xv4qs',
		'mode' => 'live',
	]
];