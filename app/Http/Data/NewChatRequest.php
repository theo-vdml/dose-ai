<?php

namespace App\Http\Data;

use Spatie\LaravelData\Data;

class NewChatRequest extends Data
{
    public function __construct(
        public string $model,
        public string $message,
    ) {}
}
