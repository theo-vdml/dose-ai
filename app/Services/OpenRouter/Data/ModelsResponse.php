<?php

namespace App\Services\OpenRouter\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ModelsResponse extends Data
{
    public function __construct(
        /** @var Collection<int, Model> */
        #[LiteralTypeScriptType('Array<Model>')]
        public Collection $data,
    ) {}
}
