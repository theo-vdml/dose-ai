<?php

namespace App\OpenRouter\Chat;

use App\OpenRouter\OpenRouterClient;
use App\OpenRouter\Stream\StreamIterator;

class ChatManager
{
    public function __construct(
        protected OpenRouterClient $client,
        protected ChatRequest $request,
    ) {}

    public function create(): ChatResponse
    {
        $payload = $this->request->toPayload();

        $response = $this->client->http()
            ->post('/chat/completions', $payload)
            ->throw()
            ->json();

        return new ChatResponse($response);
    }

    public function stream(): StreamIterator
    {
        $payload = $this->request->toPayload();
        $payload['stream'] = true;

        $response = $this->client->http()
            ->withOptions(['stream' => true])
            ->post('/chat/completions', $payload);

        return new StreamIterator($response);
    }
}
