<?php

namespace App\Services\OpenRouter\Data\Responses;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use App\Services\OpenRouter\Data\Entities\Model;

#[TypeScript]
class Models extends Data
{
    public function __construct(
        /** @var Collection<int, Model> */
        #[LiteralTypeScriptType('Array<Model>')]
        public Collection $data,
    ) {}
}
