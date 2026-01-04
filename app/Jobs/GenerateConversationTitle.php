<?php

namespace App\Jobs;

use App\Events\ConversationTitleGenerated;
use App\Models\Conversation;
use App\OpenRouter\Chat\ChatMessage;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Facades\OpenRouter;
use App\Services\ConversationContextBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateConversationTitle implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Conversation $conversation
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Safety guard - do not generate a title if one already exists
        if ($this->conversation->title !== null) {
            return;
        }

        try {

            $title = $this->generateTitle();

            if ($title === null) {
                Log::warning('Failed to generate title: empty response', [
                    'conversation_id' => $this->conversation->id,
                ]);
                return;
            }

            $this->conversation->update([
                'title' => $title,
            ]);

            broadcast(new ConversationTitleGenerated(
                userId: $this->conversation->user_id,
                conversationId: $this->conversation->id,
                title: $title
            ));
        } catch (Throwable $e) {

            Log::error('Failed to generate conversation title: ' . $e->getMessage(), [
                'conversation_id' => $this->conversation->id,
                'exception' => $e,
            ]);

            throw $e;
        }
    }

    private function generateTitle()
    {
        // Build context messages with the prompt at the end
        $messages = ConversationContextBuilder::make(
            $this->conversation,
            afterMessages: [
                'Provide a concise title for the above conversation. The title should be brief and descriptive, ideally under 10 words. Do not include any additional information or punctuation.',
            ]
        );

        // Create the chat request
        $request = new ChatRequest(
            model: $this->conversation->model_id,
            messages: $messages,
            temperature: 0.0,
            reasoningEffort: 'low',
            excludeReasoning: true,
        );

        // Send the request to OpenRouter
        $response = OpenRouter::chat($request)->create();

        $title = trim($response->firstChoice()?->message()?->content ?? '');

        if (empty($title)) {
            return null;
        }

        if (mb_strlen($title) > 64) {
            $title = mb_substr($title, 0, 61) . '...';
        }

        return $title;
    }
}
