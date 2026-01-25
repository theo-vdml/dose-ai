<?php

namespace App\OpenRouter\Facades;

use App\OpenRouter\Chat\ChatManager;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Models\ModelManager;
use App\OpenRouter\OpenRouterClient;
use App\OpenRouter\Testing\OpenRouterClientFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ChatManager chat(ChatRequest $request)
 * @method static ModelManager models()
 */
class OpenRouter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OpenRouterClient::class;
    }

    public static function fake(): OpenRouterClientFake
    {
        return tap(new OpenRouterClientFake(), function (OpenRouterClientFake $fake) {
            static::swap($fake);
        });
    }
}
