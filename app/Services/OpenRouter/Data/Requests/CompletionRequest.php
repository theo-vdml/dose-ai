<?php

namespace App\Services\OpenRouter\Data\Requests;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Max;
use App\Services\OpenRouter\Data\Entities\Message;

class CompletionRequest extends Data
{
    public function __construct(
        public string $model,

        /** @var Collection<int, Message> */
        public Collection $messages,

        #[Min(1)]
        #[Max(100000)]
        public ?int $max_tokens = 1000,

        public ?bool $stream = null,
    ) {}

    public function toApiPayload(): array
    {
        return array_filter($this->toArray(), fn($value): bool => !is_null($value));
    }
}
