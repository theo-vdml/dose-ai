<?php

namespace App\OpenRouter\Chat;

use App\OpenRouter\OpenRouterClient;
use App\OpenRouter\Stream\StreamIterator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class ChatManager
{
    public function __construct(
        protected OpenRouterClient $client,
        protected ChatRequest $request,
    ) {}

    /**
     * Creates a chat completion request.
     * @return ChatResponse
     * @throws RequestException|ConnectionException
     */
    public function create(): ChatResponse
    {
        $payload = $this->request->toPayload();

        $response = $this->client->http()
            ->post('/chat/completions', $payload)
            ->throw()
            ->json();

        return new ChatResponse($response);
    }

    /**
     * Creates a streaming chat completion request.
     * @return StreamIterator
     * @throws RequestException|ConnectionException
     */
    public function stream(): StreamIterator
    {
        $payload = $this->request->toPayload();
        $payload['stream'] = true;

        $response = $this->client->http()
            ->withOptions(['stream' => true])
            ->post('/chat/completions', $payload)
            ->throw();

        return new StreamIterator($response);
    }
}
