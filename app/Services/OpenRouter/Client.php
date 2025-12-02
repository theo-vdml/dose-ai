<?php

namespace App\Services\OpenRouter;

use App\Services\OpenRouter\Data\ChatRequest;
use App\Services\OpenRouter\Data\ChatResponse;
use App\Services\OpenRouter\Data\Message;
use App\Services\OpenRouter\Data\ModelsResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * OpenRouter API Client
 *
 * Provides methods to interact with the OpenRouter API for chat completions
 * and model information retrieval.
 */
class Client
{
    protected string $baseUrl;
    protected string $apiKey;

    /**
     * Initialize the OpenRouter client with configuration.
     *
     * @throws \RuntimeException When API key is not configured.
     */
    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.openrouter.base_url'), '/');
        $this->apiKey = config('services.openrouter.api_key')
            ?? throw new \RuntimeException('OpenRouter API key is not configured.');;
    }

    /**
     * Create a configured HTTP request instance.
     *
     * @return PendingRequest HTTP client with OpenRouter headers configured.
     */
    public function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->timeout(120);
    }

    /**
     * Send a GET request to the OpenRouter API.
     *
     * @param string $endpoint The API endpoint to request.
     * @return Response The HTTP response.
     */
    public function get(string $endpoint): Response
    {
        return $this->request()->get($this->baseUrl . '/' . ltrim($endpoint, '/'));
    }

    /**
     * Send a POST request to the OpenRouter API.
     *
     * @param string $endpoint The API endpoint to request.
     * @param array $data The data to send in the request body.
     * @return Response The HTTP response.
     */
    public function post(string $endpoint, array $data = []): Response
    {
        return $this->request()->post($this->baseUrl . '/' . ltrim($endpoint, '/'), $data);
    }

    /**
     * Retrieve all available models from OpenRouter.
     *
     * @return ModelsResponse Collection of available models.
     * @throws \Illuminate\Http\Client\RequestException When the request fails.
     */
    public function models(): ModelsResponse
    {
        $response = $this->get('models')->throw();
        return ModelsResponse::from($response->json());
    }

    /**
     * Send a chat completion request to OpenRouter.
     *
     * @param string $model The model identifier to use for completion.
     * @param string|array $messages Single message string or array of message objects.
     * @param array $options Additional options for the chat request (e.g., temperature, max_tokens).
     * @return ChatResponse The chat completion response.
     * @throws \Illuminate\Http\Client\RequestException When the request fails.
     */
    public function chat(string $model, string|array $messages, array $options = []): ChatResponse
    {
        if (is_string($messages)) {
            $messages = [['role' => 'user', 'content' => $messages]];
        }

        $request = ChatRequest::from(array_merge([
            'model' => $model,
            'messages' => Message::collect($messages)
        ], $options));

        $response = $this->post('chat/completions', $request->toArray())->throw();

        return ChatResponse::from($response->json());
    }
}
