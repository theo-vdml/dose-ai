<?php

namespace App\Providers;

use App\OpenRouter\OpenRouterClient;
use Illuminate\Support\ServiceProvider;

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
