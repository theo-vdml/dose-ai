<?php

namespace App\OpenRouter\DTO;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PerRequestLimitsData extends Data
{

    public function __construct(
        /** Maximum prompt tokens per request */
        public int $prompt_tokens,

        /** Maximum completion tokens per request */
        public int $completion_tokens,
    ){}

}
