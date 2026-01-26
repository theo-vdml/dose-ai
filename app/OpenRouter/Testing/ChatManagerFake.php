<?php

namespace App\OpenRouter\Testing;

use App\OpenRouter\Chat\ChatManager;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Chat\ChatResponse;
use App\OpenRouter\OpenRouterClient;
use App\OpenRouter\Stream\StreamIterator;
use App\OpenRouter\Stream\StreamIteratorFake;
use Exception;

class ChatManagerFake extends ChatManager
{
    protected string $responseId;

    public function __construct(
        OpenRouterClient $client,
        ChatRequest $request,
        protected string $content = 'This is a fake response.',
        protected ?string $reasoning = null,
        protected string $finishReason = 'stop',
        protected array $usage = [],
        protected ?Exception $exception = null,
    ) {
        $this->responseId = 'fake-id-'.uniqid();
        parent::__construct($client, $request);
    }

    /**
     * Mock creating a chat completion request.
     *
     * @throws Exception
     */
    public function create(): ChatResponse
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $response = [
            'id' => $this->responseId,
            'model' => $this->request->model ?? 'fake-model',
            'choices' => [
                [
                    'message' => [
                        'role' => 'assistant',
                        'content' => $this->content,
                        'reasoning' => $this->reasoning,
                    ],
                    'finish_reason' => $this->finishReason,
                    'index' => 0,
                ],
            ],
        ];

        return new ChatResponse($response);
    }

    /**
     * Mock creating a streaming chat completion request.
     *
     * @throws Exception
     */
    public function stream(): StreamIterator
    {
        if ($this->exception) {
            throw $this->exception;
        }

        $queue = [];
        if ($this->reasoning) {
            $reasoningParts = str_split($this->reasoning, 5);
            foreach ($reasoningParts as $part) {
                $queue[] = $this->makeStreamChunk('reasoning', $part);
            }
        }

        $contentParts = str_split($this->content, 5);
        foreach ($contentParts as $part) {
            $queue[] = $this->makeStreamChunk(value: $part);
        }

        $queue[] = $this->makeStreamChunk(finishReason: $this->finishReason);

        return new StreamIteratorFake($queue);
    }

    /**
     * Create a fake stream chunk.
     */
    protected function makeStreamChunk(string $key = 'content', string $value = '', ?string $finishReason = null): array
    {
        $chunk = [
            'id' => $this->responseId,
            'model' => $this->request->model ?? 'fake-model',
            'choices' => [
                [
                    'delta' => [$key => $value],
                    'index' => 0,
                ],
            ],
        ];

        if ($finishReason !== null) {
            $chunk['choices'][0]['finish_reason'] = $finishReason;
        }

        return $chunk;
    }
}
