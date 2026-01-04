<?php

namespace App\OpenRouter\Chat;

class ChatRequest
{
    /**
     * Summary of __construct
     * @param array<ChatMessage> $messages
     * @param ?string $model
     * @param float $temperature
     * @param int $maxTokens
     * @param ?string $reasoningEffort
     * @param bool $excludeReasoning
     */
    public function __construct(
        public readonly array $messages,
        public readonly ?string $model = null,
        public readonly float $temperature = 0.7,
        public readonly int $maxTokens = 0,
        public readonly ?string $reasoningEffort = null,
        public readonly bool $excludeReasoning = false,
    ) {}

    public function toPayload(): array
    {
        $payload = [
            'model' => $this->model ?? config('openrouter.default_model', 'openai/gpt-5-mini'),
            'messages' => array_map(fn(ChatMessage $msg): array => $msg->toArray(), $this->messages),
            'temperature' => $this->temperature,
            'max_tokens' => $this->maxTokens,
        ];

        if ($this->reasoningEffort !== null || $this->excludeReasoning) {
            $payload['reasoning'] = [];

            if ($this->reasoningEffort !== null) {
                $payload['reasoning']['effort'] = $this->reasoningEffort;
            }

            if ($this->excludeReasoning) {
                $payload['reasoning']['exclude'] = $this->excludeReasoning;
            }
        }

        return $payload;
    }
}
