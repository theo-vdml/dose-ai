<?php

namespace App\OpenRouter\Chat;

class ChatResponse
{
    public function __construct(
        public array $payload,
    ) {}

    /**
     * Summary of choices
     * @return array<ChatChoice>
     */
    public function choices(): array
    {
        return array_map(
            fn(array $choicePayload): ChatChoice => new ChatChoice($choicePayload),
            $this->payload['choices'] ?? []
        );
    }

    public function firstChoice(): ?ChatChoice
    {
        return $this->choices()[0] ?? null;
    }

    public function usage(): ?array
    {
        return $this->payload['usage'] ?? null;
    }

    public function raw(): array
    {
        return $this->payload;
    }
}
