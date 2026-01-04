<?php

namespace App\Services;

use App\Models\Conversation;
use App\OpenRouter\Chat\ChatMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ConversationContextBuilder
{

    /**
     * Build the context messages for a conversation.
     * @param Conversation $conversation
     * @param array<int, ChatMessage|string> $beforeMessages
     * @param array<int, ChatMessage|string> $afterMessages
     * @param ?string $startsFrom
     * @return array<ChatMessage>
     */
    public static function make(
        Conversation $conversation,
        array $beforeMessages = [],
        array $afterMessages = [],
        ?string $startsFrom = null
    ): array {
        $conversationContext = $conversation->contextMessages($startsFrom);

        return self::mergeMessages(
            $beforeMessages,
            $conversationContext,
            $afterMessages
        );
    }

    protected static function mergeMessages(
        array $beforeMessages,
        array $conversationMessages,
        array $afterMessages
    ): array {
        return array_merge(
            self::normalizeMessages($beforeMessages),
            $conversationMessages,
            self::normalizeMessages($afterMessages)
        );
    }

    protected static function normalizeMessages(array $messages): array
    {
        $normalized = [];

        foreach ($messages as $msg) {

            if ($msg instanceof ChatMessage) {
                $normalized[] = $msg;
                continue;
            }

            if (is_string($msg)) {
                if (!View::exists($msg)) {
                    $normalized[] = ChatMessage::system($msg);
                    continue;
                }

                $content = trim(View::make($msg)->render());

                if (!empty($content)) {
                    $normalized[] = ChatMessage::system($content);
                }

                continue;
            }

            throw new \InvalidArgumentException('Invalid message type provided to ConversationContextBuilder.');
        }

        return $normalized;
    }
}
