<?php

return [
    'development' => [
        'base_url' => env('DEV_MPESA_BASE_URL', 'YOUR_DEV_MPESA_BASE_URL'),
        'consumer_key' => env('DEV_MPESA_CONSUMER_KEY', 'YOUR_DEV_MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('DEV_MPESA_CONSUMER_SECRET', 'YOUR_DEV_MPESA_CONSUMER_SECRET'),
        'security_credential' => env('DEV_SECURITY_CREDENTIAL', 'YOUR_DEV_SECURITY_CREDENTIAL'),
    ],
    
    'production' => [
        'base_url' => env('PROD_MPESA_BASE_URL', 'YOUR_PROD_MPESA_BASE_URL'),
        'consumer_key' => env('PROD_MPESA_CONSUMER_KEY', 'YOUR_PROD_MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('PROD_MPESA_CONSUMER_SECRET', 'YOUR_PROD_MPESA_CONSUMER_SECRET'),
        'security_credential' => env('PROD_SECURITY_CREDENTIAL', 'YOUR_PROD_SECURITY_CREDENTIAL'),
    ],
];