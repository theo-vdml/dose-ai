<?php

namespace App\Jobs;

use App\Events\ConversationTitleGenerated;
use App\Models\Conversation;
use App\Services\OpenRouter\Data\Entities\Message;
use App\Services\OpenRouter\Facades\OpenRouter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        // Safty guard: if the conversation already has a title, do nothing
        if ($this->conversation->title !== null) {
            return;
        }

        // Fetch the active message chain
        $messages = $this->conversation->activeMessageChain()->all();

        // Generate a title using OpenRouter
        $response = OpenRouter::completion()
            ->model($this->conversation->model_id)
            ->messages([
                ...$messages,
                Message::system(
                    "Provide a concise title for the user's conversation in 5 words or less."
                )
            ])->maxTokens(32)->reasoningEffort('none')->create();

        // Extract the title from the response
        $title = trim($response->content() ?? 'Untitled Conversation');

        // Update the conversation with the generated title
        $this->conversation->update([
            'title' => $title,
        ]);

        broadcast(new ConversationTitleGenerated(
            userId: $this->conversation->user_id,
            conversationId: $this->conversation->id,
            title: $title
        ));
    }
}
