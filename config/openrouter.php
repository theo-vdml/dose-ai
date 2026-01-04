<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenRouter API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for OpenRouter API integration.
    | This includes the API key, base URL, and default model to use.
    |
    */

    'api_key' => env('OPENROUTER_API_KEY', ''),

    'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),

    'default_model' => env('OPENROUTER_DEFAULT_MODEL', 'openai/gpt-5-mini'),

];
