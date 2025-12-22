<?php

namespace App\Services\OpenRouter\Facades;

use App\Services\OpenRouter\Client;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CompletionService completion()
 * @method static ModelService models()
 */
class OpenRouter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
