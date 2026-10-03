<?php

declare(strict_types=1);

return [
    'default_locale' => env('AI_INFLECTOR_LOCALE', 'nl'),

    /*
    |--------------------------------------------------------------------------
    | Gemini API credentials and model
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_INFLECTOR_MODEL', 'gemini-2.0-flash'),
        ],
    ],
];
