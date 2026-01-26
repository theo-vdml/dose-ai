<?php

namespace App\OpenRouter\DTO;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PricingData extends Data
{
    public function __construct(
        public string $prompt,
        public string $completion,
        public ?string $request = null,
        public ?string $image = null,
        public ?string $image_token = null,
        public ?string $image_output = null,
        public ?string $audio = null,
        public ?string $input_audio_cache = null,
        public ?string $web_search = null,
        public ?string $internal_reasoning = null,
        public ?string $input_cache_read = null,
        public ?string $input_cache_write = null,
        public ?int $discount = null,
    ) {}
}
