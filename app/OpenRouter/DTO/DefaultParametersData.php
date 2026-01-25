<?php

namespace App\OpenRouter\DTO;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DefaultParametersData extends Data
{
    public function __construct(
        #[Numeric, Min(0), Max((2))]
        public ?float $temperature = null,

        #[Numeric, Min(0), Max((1))]
        public ?float $top_p = null,

        #[Numeric, Min(-2), Max((2))]
        public ?float $frequency_penalty = null,
    ){}
}
