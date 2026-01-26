<?php

namespace App\OpenRouter;

use App\OpenRouter\Chat\ChatManager;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Models\ModelManager;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenRouterClient
{
    protected ModelManager $modelManager;

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl = 'https://openrouter.ai/api/v1/'
    ) {}

    public function models(): ModelManager
    {
        if (! isset($this->modelManager)) {
            $this->modelManager = new ModelManager($this);
        }

        return $this->modelManager;
    }

    public function chat(ChatRequest $request): ChatManager
    {
        return new ChatManager($this, $request);
    }

    public function http(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->baseUrl(rtrim($this->baseUrl, '/').'/');
    }
}
