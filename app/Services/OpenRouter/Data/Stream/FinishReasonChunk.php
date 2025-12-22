<?php

namespace App\Services\OpenRouter\Data\Stream;

class FinishReasonChunk extends BaseChunk
{
    public function __construct(
        public string $router,
        public string $model,
    ) {}

    protected function getType(): string
    {
        return 'finish_reason';
    }
}
