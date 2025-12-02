<?php

namespace App\Services\OpenRouter\Data;

use Spatie\LaravelData\Data;

class ChatResponse extends Data
{
    public function __construct(
        public string $id,
        public string $model,

        /** @var array<int, Choice> */
        public array $choices,

        public Usage $usage,
        public int $created,
    ) {}
}
