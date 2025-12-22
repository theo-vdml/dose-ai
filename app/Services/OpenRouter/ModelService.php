<?php

namespace App\Services\OpenRouter;

use App\Services\OpenRouter\Data\Responses\Models;

class ModelService
{
    public const DEFAULT_MODEL = 'openai/gpt-5-mini';

    public function __construct(
        protected Client $client
    ) {}

    public function all(): Models
    {
        $response = $this->client->get('/models')->throw();

        return Models::from($response->json());
    }

    public function default()
    {
        return self::DEFAULT_MODEL;
    }
}
