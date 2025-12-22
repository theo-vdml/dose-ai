<?php

namespace App\Services\OpenRouter\Data\Stream;

class UsageChunk extends BaseChunk
{
    public function __construct(
        public int $promptTokens,
        public int $completionTokens,
        public int $totalTokens,
    ) {}

    protected function getType(): string
    {
        return 'usage';
    }
}
