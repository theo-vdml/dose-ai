<?php

namespace App\Services\OpenRouter\Data\Entities;

use Spatie\LaravelData\Data;

class Usage extends Data
{
    public function __construct(
        public int $promptTokens,
        public int $completionTokens,
        public int $totalTokens,
    ) {}
}
