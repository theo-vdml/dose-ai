<?php

namespace App\Http\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\Validation\Present;

class MessageData extends Data
{
    public function __construct(
        #[In(['user', 'assistant', 'system'])]
        public string $role,

        public ?string $content = '',

    ) {}
}
