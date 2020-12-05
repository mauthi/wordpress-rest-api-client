<?php

use Vnn\WpApiClient\Auth\WpJWTAuth;

return [

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | If true you get debug information (e.g. for http requests)
    |
    */

    'debug' => env('WP_REST_API_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Wordpress url
    |--------------------------------------------------------------------------
    |
    | Url to your wordpress installation
    |
    */

    'url' => env('WP_REST_API_URL', 'http://yourwordpresspage.com'),

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | your api_key and secret (see https://github.com/WP-API/jwt-auth)
    |
    */
    
    'key' => env('WP_REST_API_KEY'),
    'secret' => env('WP_REST_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Custom Post Types
    |--------------------------------------------------------------------------
    |
    | if you are using custom post types you can specify the api slugs here
    |
    */

    'customPostTypes' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Taxonomies
    |--------------------------------------------------------------------------
    |
    | if you are using custom taxonomies you can specify the api slugs here
    |
    */

    'customTaxonomies' => [],

    /*
    |--------------------------------------------------------------------------
    | Auth class
    |--------------------------------------------------------------------------
    |
    | Which class should be used for authentication (either WpJWTAuth::class or
    | your custom implementation)
    |
    */
    'authClass' => WpJWTAuth::class,

];
