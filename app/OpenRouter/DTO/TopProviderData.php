<?php

namespace App\OpenRouter\DTO;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TopProviderData extends Data
{
    public function __construct(
        /** Whether the top provider moderates content */
        public bool $is_moderated,

        /** Context length from the top provider */
        public ?int $context_length = null,

        /** Maximum completion tokens from the top provider */
        public ?int $max_completion_tokens = null,
    ){}
}
