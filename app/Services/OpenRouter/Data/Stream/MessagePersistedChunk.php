<?php

namespace App\Services\OpenRouter\Data\Stream;

use App\Models\Message;

class MessagePersistedChunk extends BaseChunk
{
    public function __construct(
        public Message $message,
    ) {}

    protected function getType(): string
    {
        return 'message_persisted';
    }
}
