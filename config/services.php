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
        'scheme' => 'https',
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
        'api_key' => env('MSG91_API_KEY'),
        'sender_id' => env('MSG91_SENDER_ID'),
        'route' => env('MSG91_ROUTE', '4'),
        'country' => env('MSG91_COUNTRY', '91'),
        'authkey' => env('MSG91_AUTHKEY'),
        'cookie' => env('MSG91_COOKIE'),
    ],

    'facebook' => [
        'auth_token' => env('FACEBOOK_AUTH_TOKEN'),
        'whatsapp_number' => env('WHATSAPP_RECIPIENT_NUMBER'),
        'phone_number_id' => env('FACEBOOK_PHONE_NUMBER_ID'),
    ],

];
