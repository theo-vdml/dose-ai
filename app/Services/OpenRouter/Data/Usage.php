<?php

namespace App\Services\OpenRouter\Data;

use Spatie\LaravelData\Data;

class Usage extends Data
{
    public function __construct(
        public int $prompt_tokens,
        public int $completion_tokens,
        public int $total_tokens,
    ) {}
}
