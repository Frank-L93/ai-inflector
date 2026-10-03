<?php

declare(strict_types=1);

return [

    env('AI_INFLECTOR_DRIVER', 'gemini'),

    'default_locale' => env('AI_INFLECTOR_LOCALE', 'nl'),

    /*
    |--------------------------------------------------------------------------
    | API Keys & Configs for each driver
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_INFLECTOR_MODEL', 'gemini-2.0-flash'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'prefix' => 'ai_inflector_',
        'store' => env('AI_INFLECTOR_CACHE_STORE', null), // null uses the default Laravel cache
    ],
];
