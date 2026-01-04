<?php

namespace App\OpenRouter\Chat;

class ChatChoice
{
    public function __construct(
        public array $payload,
    ) {}

    public function message(): ?ChatMessage
    {
        if (!isset($this->payload['message'])) {
            return null;
        }

        return new ChatMessage(
            role: $this->payload['message']['role'] ?? 'assistant',
            content: $this->payload['message']['content'] ?? '',
            reasoning: $this->payload['message']['reasoning'] ?? null,
        );
    }

    public function finishReason(): ?string
    {
        return $this->payload['finish_reason'] ?? null;
    }
}
