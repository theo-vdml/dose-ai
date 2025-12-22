<?php

namespace App\Services\OpenRouter\Data\Stream;

class ReasoningChunk extends BaseChunk
{
    public function __construct(
        public string $delta,
    ) {}

    protected function getType(): string
    {
        return 'reasoning';
    }
}
