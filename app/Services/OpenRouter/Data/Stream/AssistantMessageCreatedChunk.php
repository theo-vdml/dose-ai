<?php

namespace App\Services\OpenRouter\Data\Stream;

use App\Models\Message;

class AssistantMessageCreatedChunk extends BaseChunk
{
    public function __construct(
        public Message $message,
    ) {}

    protected function getType(): string
    {
        return 'assistant_message_created';
    }
}
