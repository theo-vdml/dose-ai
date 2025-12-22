<?php

namespace App\Services\OpenRouter\Data\Stream;

use Spatie\LaravelData\Data;

abstract class BaseChunk extends Data
{

    abstract protected function getType(): string;

    public function toFrontend(): array
    {
        return [
            'type' => $this->getType(),
            'data' => $this->toArray(),
        ];
    }
}
