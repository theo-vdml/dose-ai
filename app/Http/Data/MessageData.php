<?php

namespace App\Http\Data;

use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class MessageData extends Data
{
    public function __construct(
        #[In(['user', 'assistant', 'system'])]
        public string $role,

        public ?string $content = '',

    ) {}
}
