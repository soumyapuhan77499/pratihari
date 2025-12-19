<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
   'msg91' => [
        'auth_key'       => env('MSG91_AUTHKEY'),
        'wa_template'    => env('MSG91_WA_TEMPLATE'),
        'wa_namespace'   => env('MSG91_WA_NAMESPACE'),
        'wa_number'      => env('MSG91_WA_NUMBER'),            // may include +; we'll normalize
        'wa_lang_code'   => env('MSG91_WA_LANG_CODE', 'en_US'),
        'wa_lang_policy' => env('MSG91_WA_LANG_POLICY', 'deterministic'),
        // how many variables your approved template’s BODY expects: 1 (OTP only) or 2 (OTP + token)
        'wa_body_params' => (int) env('MSG91_WA_BODY_PARAMS', 1),
    ],

      'firebase' => [
        'pratihari' => [
            'credentials' => env('FIREBASE_PRATIHARI_CREDENTIALS_PATH'),
        ],
        'user' => [
            'credentials' => env('FIREBASE_USER_CREDENTIALS_PATH'),
        ],
    ],

    
];
