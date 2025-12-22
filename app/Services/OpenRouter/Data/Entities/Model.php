<?php

namespace App\Services\OpenRouter\Data\Entities;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class Model extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
    ) {}
}
