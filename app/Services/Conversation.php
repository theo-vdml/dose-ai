<?php

namespace App\Services;

use App\Services\OpenRouter\Client as OpenRouterClient;
use App\Services\OpenRouter\Data\ModelsResponse;
use Illuminate\Support\Facades\Cache;

class Conversation
{
    public const DEFAULT_MODEL = 'openai/gpt-5-mini';
    protected OpenRouterClient $client;

    public function __construct(OpenRouterClient $client)
    {
        $this->client = $client;
    }

    public function getDefaultModel(): string
    {
        return self::DEFAULT_MODEL;
    }

    public function listModels(): ModelsResponse
    {
        return Cache::remember('openrouter.models', now()->addHour(), function (): ModelsResponse {
            return $this->client->models();
        });
    }

    public function sendMessage()
    {
        // Implementation for sending a message in a conversation
    }
}
