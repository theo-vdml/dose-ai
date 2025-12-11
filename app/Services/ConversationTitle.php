<?php

namespace App\Services;

use App\Services\OpenRouter\Client as OpenRouterClient;

class ConversationTitle
{
    public function __construct(
        private OpenRouterClient $client
    ) {}

    public function fromMessage(string $message, string $model): string
    {
        $prompt = "Generate a concise and relevant title in 5 to 8 words, in the language of the message, for a conversation based on the following user message:\n\n\"{$message}\"\n\n";

        $response = $this->client->chat($model, $prompt);
        return $response->choices[0]->message->content ?? 'Untitled Conversation';
    }
}
