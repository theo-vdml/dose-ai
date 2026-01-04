<?php

namespace App\OpenRouter\Models;

use App\OpenRouter\OpenRouterClient;

class ModelManager
{
    public function __construct(
        protected OpenRouterClient $client
    ) {}

    public function defaultId(): ?string
    {
        return config('openrouter.default_model', 'openai/gpt-5-mini');
    }

    public function list(): ModelList
    {
        $response = $this->client
            ->http()
            ->get('/models')
            ->throw()
            ->json();

        return new ModelList($response['data']);
    }
}
