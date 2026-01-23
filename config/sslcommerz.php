<?php
return [
    'store_id' => env('SSLC_STORE_ID'),
    'store_password' => env('SSLC_STORE_PASSWORD'),

    'init_url' => env('SSLC_SANDBOX', true)
        ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
        : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',

    'validation_url' => env('SSLC_SANDBOX', true)
        ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
        : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
];