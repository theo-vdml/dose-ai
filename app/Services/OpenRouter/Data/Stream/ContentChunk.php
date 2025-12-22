<?php

namespace App\Services\OpenRouter\Data\Stream;

class ContentChunk extends BaseChunk
{
    public function __construct(
        public string $delta,
    ) {}

    protected function getType(): string
    {
        return 'content';
    }
}
