<?php

namespace App\Services\OpenRouter\Data\Entities;

use Spatie\LaravelData\Data;

class Choice extends Data
{
    public function __construct(
        public int $index,
        public Message $message,
        public ?string $finishReason = null,
    ) {}
}
