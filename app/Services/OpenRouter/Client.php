<?php

namespace App\Services\OpenRouter;

class Client
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.openrouter.base_url'), '/');
        $this->apiKey = config('services.openrouter.api_key')
            ?? throw new \RuntimeException('OpenRouter API key is not configured.');;
    }

    public function request(): \Illuminate\Http\Client\PendingRequest
    {
        return \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ]);
    }

    public function get(string $endpoint, array|string|null $query = null, array $options = []): \Illuminate\Http\Client\Response
    {
        $endpoint = ltrim($endpoint, '/');
        $url = $this->baseUrl . '/' . $endpoint;
        $timeout = $options['timeout'] ?? 0;
        unset($options['timeout']);
        return $this->request()
            ->timeout($timeout)
            ->withOptions($options)
            ->get($url, $query);
    }

    public function post(string $endpoint, array $data = [], array $options = []): \Illuminate\Http\Client\Response
    {
        $endpoint = ltrim($endpoint, '/');
        $url = $this->baseUrl . '/' . $endpoint;
        $timeout = $options['timeout'] ?? 0;
        unset($options['timeout']);
        return $this->request()
            ->timeout($timeout)
            ->withOptions($options)
            ->post($url, $data);
    }

    public function models(): ModelService
    {
        return new ModelService($this);
    }

    public function completion(): CompletionService
    {
        return new CompletionService($this);
    }
}
