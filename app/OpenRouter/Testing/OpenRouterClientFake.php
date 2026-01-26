<?php

namespace App\OpenRouter\Testing;

use App\OpenRouter\Chat\ChatManager;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\DTO\ModelData;
use App\OpenRouter\Models\ModelManager;
use App\OpenRouter\OpenRouterClient;
use Closure;
use Exception;
use Illuminate\Support\Testing\Fakes\Fake;

class OpenRouterClientFake extends OpenRouterClient implements Fake
{
    protected Closure|string|null $contentFactory = null;

    protected Closure|string|null $reasoningFactory = null;

    protected array $responseOptions = [];

    protected ?Exception $exception = null;

    protected array $recordedRequests = [];

    public function __construct()
    {
        parent::__construct('fake-api-key');
    }

    private function record(ChatRequest $request): void
    {
        $this->recordedRequests[] = $request;
    }

    /**
     * Set the models to be returned by the fake ModelManager.
     *
     * @param  array<int, ModelData>  $models
     */
    public function withModels(array $models): static
    {
        $this->models()->withModels($models);

        return $this;
    }

    /**
     * Set an exception to be thrown by the fake ModelManager.
     */
    public function modelsShouldThrow(Exception $exception): static
    {
        $this->models()->shouldThrow($exception);

        return $this;
    }

    /**
     * Access the fake ModelManager instance.
     *
     * @return ModelManagerFake
     */
    public function models(): ModelManager
    {
        if (! isset($this->modelManager)) {
            $this->modelManager = new ModelManagerFake($this);
        }

        return $this->modelManager;
    }

    /**
     * Configure the fake chat response.
     *
     * @param  Closure|string|null  $contentFactory  A closure or string to generate the response content.
     * @param  Closure|string|null  $reasoningFactory  A closure or string to generate the reasoning.
     * @param  array  $options  Additional response options like 'finish_reason' and 'usage'.
     */
    public function respondWith(
        Closure|string|null $contentFactory = null,
        Closure|string|null $reasoningFactory = null,
        array $options = []
    ): static {
        $this->contentFactory = $contentFactory;
        $this->reasoningFactory = $reasoningFactory;
        $this->responseOptions = array_merge($this->responseOptions, $options);

        return $this;
    }

    /**
     * Set an exception to be thrown during chat requests.
     */
    public function shouldThrow(Exception $exception): static
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * Resolve the content for the fake chat response.
     */
    protected function resolveContent(ChatRequest $request): string
    {
        if ($this->contentFactory instanceof Closure) {
            return call_user_func($this->contentFactory, $request);
        } elseif (is_string($this->contentFactory)) {
            return $this->contentFactory;
        }

        return "This is a fake response from $request->model.";
    }

    /**
     * Resolve the reasoning for the fake chat response.
     */
    protected function resolveReasoning(ChatRequest $request): ?string
    {
        if ($this->reasoningFactory instanceof Closure) {
            return call_user_func($this->reasoningFactory, $request);
        } elseif (is_string($this->reasoningFactory)) {
            return $this->reasoningFactory;
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function chat(ChatRequest $request): ChatManager
    {
        $this->record($request);
        $content = $this->resolveContent($request);
        $reasoning = $this->resolveReasoning($request);
        $finishReason = $this->responseOptions['finish_reason'] ?? 'stop';
        $usage = $this->responseOptions['usage'] ?? [];

        return new ChatManagerFake(
            $this,
            $request,
            $content,
            $reasoning,
            $finishReason,
            $usage,
            $this->exception
        );
    }
}
