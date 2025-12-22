<?php

namespace App\Services\OpenRouter\Data\Stream;

use App\Models\Message;

class UserMessageCreatedChunk extends BaseChunk
{
    public function __construct(
        public Message $message,
    ) {}

    protected function getType(): string
    {
        return 'user_message_created';
    }
}
