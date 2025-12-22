<?php

namespace App\Services\OpenRouter\Data\Responses;

use App\Services\OpenRouter\Data\Entities\{Usage, Choice};
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class Completion extends Data
{
    public function __construct(
        public string $id,
        public string $model,
        public string $object,

        public int $created,

        /** @var Collection<int, Choice> */
        public Collection $choices,

        public Usage $usage,
    ) {}

    public function content(): string
    {
        $first = $this->choices->first();
        return $first->message->content ?? '';
    }

    public function finishReason(): string
    {
        $first = $this->choices->first();
        return $first->finishReason ?? '';
    }
}
