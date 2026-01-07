<?php

namespace App\OpenRouter\Testing;

use App\OpenRouter\Chat\ChatManager;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Chat\ChatResponse;
use App\OpenRouter\Models\ModelList;
use App\OpenRouter\Models\ModelManager;
use App\OpenRouter\OpenRouterClient;
use App\OpenRouter\Stream\StreamAccumulator;
use App\OpenRouter\Stream\StreamChunkFactory;
use App\OpenRouter\Stream\StreamIterator;
use Illuminate\Support\Testing\Fakes\Fake;
use Closure;

class OpenRouterFake extends OpenRouterClient implements Fake
{
    protected array $fakeModels = [];
    protected ?Closure $fakeContentCallback = null;
    protected ?Closure $fakeReasoningCallback = null;
    protected string $fakeFinishReason = 'stop';

    protected array $recordedRequests = [];

    public function __construct()
    {
        parent::__construct('fake-api-key');
    }

    public function shouldReturnModels(array $models): static
    {
        $this->fakeModels = $models;
        return $this;
    }

    public function shouldReturnChatContent(Closure|string $callback): static
    {
        $this->fakeContentCallback = is_string($callback) ? fn() => $callback : $callback;
        return $this;
    }

    public function shouldReturnChatReasoning(Closure|string $callback): static
    {
        $this->fakeReasoningCallback = is_string($callback) ? fn() => $callback : $callback;
        return $this;
    }

    public function shouldReturnChatFinishReason(string $reason): static
    {
        $this->fakeFinishReason = $reason;
        return $this;
    }

    public function models(): ModelManager
    {

        return new class($this, $this->fakeModels) extends ModelManager {

            public function __construct(
                OpenRouterClient $client,
                protected array $fakeModels
            ) {
                return parent::__construct($client);
            }

            public function list(): ModelList
            {
                return new ModelList($this->fakeModels);
            }
        };
    }

    public function chat(ChatRequest $request): ChatManager
    {
        $this->recordedRequests[] = $request;
        $content = $this->fakeContentCallback?->call($this, $request) ?? 'This is a fake response.';
        $reasoning = $this->fakeReasoningCallback?->call($this, $request) ?? null;

        return new class($this, $request, $content, $reasoning, $this->fakeFinishReason) extends ChatManager {

            public function __construct(
                OpenRouterClient $client,
                ChatRequest $request,
                protected string $fakeContent,
                protected ?string $fakeReasoning,
                protected string $fakeFinishReason
            ) {
                parent::__construct($client, $request);
            }

            public function create(): ChatResponse
            {
                $response = [
                    'id' => 'fake-id-' . uniqid(),
                    'model' => $this->request->model ?? 'fake-model',
                    'choices' => [
                        [
                            'message' => [
                                'role' => 'assistant',
                                'content' => $this->fakeContent,
                                'reasoning' => $this->fakeReasoning,
                            ],
                            'finish_reason' => $this->fakeFinishReason,
                            'index' => 0,
                        ]
                    ]
                ];

                return new ChatResponse($response);
            }

            public function stream(): StreamIterator
            {
                $queue = [];
                $id = 'fake-id-' . uniqid();

                $makePart = fn($delta, $finish = null) => [
                    'id' => $id,
                    'model' => $this->request->model ?? 'fake-model',
                    'choices' => [['delta' => $delta, 'finish_reason' => $finish, 'index' => 0]],
                ];

                if ($this->fakeReasoning) {
                    $reasoningParts = str_split($this->fakeReasoning, 5);
                    foreach ($reasoningParts as $part) {
                        $queue[] = $makePart(['reasoning' => $part]);
                    }
                }

                $contentParts = str_split($this->fakeContent, 5);
                foreach ($contentParts as $part) {
                    $queue[] = $makePart(['content' => $part]);
                }

                $queue[] = $makePart([], $this->fakeFinishReason);

                return new class($queue) extends StreamIterator {

                    public function __construct(
                        protected array $fakeDataQueue,
                        ?StreamAccumulator $accumulator = null
                    ) {
                        $this->accumulator = $accumulator ?? new StreamAccumulator();
                    }

                    protected function parseStream(): \Generator
                    {
                        foreach ($this->fakeDataQueue as $data) {
                            $chunks = StreamChunkFactory::from($data);
                            $this->accumulator->add($chunks);
                            foreach ($chunks as $chunk) {
                                yield $chunk;
                            }
                        }
                    }
                };
            }
        };
    }
}
