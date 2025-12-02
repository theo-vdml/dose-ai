<?php

namespace App\Services\OpenRouter\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ChatRequest extends Data
{
    public function __construct(
        public string $model,

        /** @var Collection<int, Message> */
        public Collection $messages,

        public ?float $temperature = null,
    ) {}
}
