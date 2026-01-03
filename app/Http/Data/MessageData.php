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

        #[Uuid]
        public ?string $id = null,

        #[Uuid]
        public ?string $conversation_id = null,

        public ?string $content = '',

        #[Uuid, Exists(table: 'messages', column: 'id')]
        public ?string $parent_message_id = null,

        public ?string $reasoning = null,

        public ?string $created_at = null,

        public ?string $updated_at = null,
    ) {}
}
