<?php

return [
    'enabled'             => env('MAILCHIMP_MARKETING_ENABLED', true) === true,
    'api_key'             => env('MAILCHIMP_API_KEY', '157f6e213a55194b6c5d82ab58cd9a53-us8'),
    'server_prefix'       => env('MAILCHIMP_SERVER_PREFIX', 'us8'),
    'api_url'             => 'https://' . env('MAILCHIMP_SERVER_PREFIX', 'us8') . '.api.mailchimp.com/3.0/',
    'opt_in_status'       => env('MAILCHIMP_OPT_IN_BY_DEFAULT', true) === true,
    'currency'            => config('cart.currency', 'USD'),
    'money_format'        => config('cart.currency_symbol', '$'),
    'email_type_option'   => env('MAILCHIMP_EMAIL_TYPE_OPTION', false) === true,
    'permission_reminder' => env('MAIL_PERMISSION_REMINDER', 'You are receiving this email from activity you performed on rebelsmuggling.com.'),
    'domain'              => config('app.url'),
    'platform'            => 'fountainheadme.com',
    'primary_locale'      => config('app.locale', 'en'),
    'store_email'         => env('DEFAULT_EMAIL_RECIEVER', 'support@rebelsmuggling.com'),
    'abandoned_cart' => [
        'tag'                  => 'abandoned-cart',
        'discount'             => 10, //must be whole number to represent percentage.. ie. 10% == 10
        'url_time'             => now()->addDays(4),
        'apply_discount_after' => now()->addDays(3),
        'tracking_params'      => '&utm_source=acemail&utm_medium=email&utm_content=inital_email&utm_campaign=abandonedCart',
        'campaign_id'          => env('MAILCHIMP_ABANDONED_CART_CAMPAIGN_ID', '11ea0e37db'),
        'campaign_emails'      => [
            env('MAILCHIMP_ABANDONED_CART_CAMPAIGN_EMAIL_1_ID', 'd21f1f5cbe'),
            env('MAILCHIMP_ABANDONED_CART_CAMPAIGN_EMAIL_2_ID', 'e84e62fd96'),
            env('MAILCHIMP_ABANDONED_CART_CAMPAIGN_EMAIL_3_ID', '734ad49d44'),
        ],
    ],
    'newsletter' => [
        'tag'              => 'newsletter-signup',
        'discount'         => 10, //must be whole number to represent percentage.. ie. 10% == 10
        'promo_expiration' => 7, //days from newsletter signup
        'signup_message'   => 'Thank you for subscribing to our newsletter',
        'failure_message'  => 'We are having issues signing you up at the moment, please ensure your email address is valid and try again.',
    ],
    'contact' => [
        'company'  => config('app.name'),
        'phone'    => env('CONTACT_NUMBER', '8448418383'),
        'address1' => env('CONTACT_ADDRESS_1', '4421 Giannecchini'),
        'address2' => env('CONTACT_ADDRESS_2', 'Lane #A'),
        'city'     => env('CONTACT_CITY', 'Stockton'),
        'state'    => env('CONTACT_STATE', 'CA'),
        'zip'      => env('CONTACT_ZIP', '95206'),
        'country'  => env('CONTACT_COUNTRY', 'US'),
    ],

    'campaign_defaults' => [
        'from_name'  => env('MAIL_FROM_NAME', 'Rebel Smuggling'),
        'from_email' => env('MAIL_FROM_ADDRESS', 'support@rebelsmuggling.com'),
        'subject'    => env('MAIL_DEFAULT_SUBJECT', 'Supplies galore right to your front door'),
        'language'   => config('app.locale', 'en'),
    ],
];
