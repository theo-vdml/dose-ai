<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\OpenRouter\OpenRouterClient;

class OpenRouterServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(OpenRouterClient::class, function () {
            return new OpenRouterClient(
                apiKey: config('openrouter.api_key'),
                baseUrl: config('openrouter.base_url'),
            );
        });
    }
}
