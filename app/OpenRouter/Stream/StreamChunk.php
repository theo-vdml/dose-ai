<?php

namespace App\OpenRouter\Stream;

class StreamChunk
{
    public function __construct(
        public string $type,
        public array $data,
    ) {}

    public function type(): string
    {
        return $this->type;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }

    public static function content(string $delta): self
    {
        return new self(
            type: 'content',
            data: ['delta' => $delta],
        );
    }

    public static function reasoning(string $delta): self
    {
        return new self(
            type: 'reasoning',
            data: ['delta' => $delta],
        );
    }

    public static function finishReason(string $router, string $model): self
    {
        return new self(
            type: 'finish_reason',
            data: [
                'router' => $router,
                'model' => $model,
            ],
        );
    }

    public static function usage(int $promptTokens, int $completionTokens, int $totalTokens): self
    {
        return new self(
            type: 'usage',
            data: [
                'prompt_tokens' => $promptTokens,
                'completion_tokens' => $completionTokens,
                'total_tokens' => $totalTokens,
            ],
        );
    }
}
