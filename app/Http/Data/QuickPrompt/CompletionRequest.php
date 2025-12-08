<?php

namespace App\Http\Data\QuickPrompt;

use Spatie\LaravelData\Data;

class CompletionRequest extends Data
{
    public function __construct(
        public string $model,
        public string $message,
    ) {}
}
