<?php

namespace App\Services;

use App\Services\OpenRouter\Client;
use App\Services\OpenRouter\Data\ModelsResponse;
use Illuminate\Support\Facades\Cache;

class QuickPrompt
{
    public const DEFAULT_MODEL = 'openai/gpt-5-mini';
    protected Client $client;

    public function __construct(Client $client)
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

    public function sendMessage(string $message, ?string $model = null): string
    {
        $model = $model ?? self::DEFAULT_MODEL;

        $response = $this->client->chat($model, $message);

        return $response->choices[0]->message->content ?? '';
    }
}
