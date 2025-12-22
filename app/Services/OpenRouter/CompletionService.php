<?php

namespace App\Services\OpenRouter;

use App\Services\OpenRouter\Data\Requests\CompletionRequest;
use App\Services\OpenRouter\Data\Responses\Completion;
use Illuminate\Http\Client\Response;

class CompletionService
{
    protected const ENDPOINT = '/chat/completions';
    protected CompletionRequest $request;

    public function __construct(
        protected Client $client
    ) {
        $this->request = new CompletionRequest(
            model: '',
            messages: collect([]),
        );
    }

    protected function post(array $data = [], array $options = []): Response
    {
        return $this->client->post(self::ENDPOINT, $data, $options);
    }

    public function model(string $model): self
    {
        $this->request->model = $model;
        return $this;
    }

    public function messages(array $messages): self
    {
        $this->request->messages = collect($messages);
        return $this;
    }

    public function maxTokens(int $maxTokens): self
    {
        $this->request->max_tokens = $maxTokens;
        return $this;
    }

    public function create(): Completion
    {
        $response =  $this->post($this->request->toApiPayload(), options: [
            'timeout' => 300,
        ])->throw();

        return Completion::from($response->json());
    }

    public function stream(): StreamIterator
    {
        $this->request->stream = true;
        $response = $this->post($this->request->toApiPayload(), [
            'stream' => true,
        ])->throw();

        return new StreamIterator($response);
    }
}
