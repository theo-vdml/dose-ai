<?php

namespace App\Http\Data\QuickPrompt;

class CompletionRequest
{
    public function __construct(
        public string $model,
        public string $message,
    ) {}
}
