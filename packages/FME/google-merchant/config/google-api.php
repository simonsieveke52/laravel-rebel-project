<?php 

return [

	'config_path' => config_path('google-json-config/service-account.json'),

	'merchant-info' => (array) json_decode(
		file_get_contents(config_path('google-json-config/merchant-info.json'))
	),

	'service-account' => config_path('/google-json-config/service-account.json')
];

