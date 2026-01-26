<?php

namespace App\Services;

use App\Http\Data\ConversationData;
use App\Http\Data\MessageData;
use App\Jobs\GenerateConversationTitle;
use App\Models\Conversation;
use App\Models\Message;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\DTO\ModelData;
use App\OpenRouter\Facades\OpenRouter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\ServerEvent;
use Throwable;

class ConversationService
{
    /**
     * Get the list of available models from OpenRouter API, cached for 1 hour.
     *
     * @return Collection<int, ModelData>
     */
    public function getAvailableModels(): Collection
    {
        return Cache::remember('available_models', 60 * 60, function () {
            return OpenRouter::models()->list();
        });
    }

    /**
     * Get the default model ID, falling back to the first available model if the configured one is not found.
     *
     * @return string
     * @throws RuntimeException
     */
    public function getDefaultModelId(): string
    {
        $preferredModelId = config('openrouter.default_model');

        $availableModels = $this->getAvailableModels();

        $exists = $availableModels->contains('id', $preferredModelId);

        if ($exists) {
            return $preferredModelId;
        }

        Log::warning("Configured default model [$preferredModelId] is no longer available on OpenRouter. Falling back to first available model.");

        if ($availableModels->isEmpty()) {
            throw new RuntimeException("No available models found from OpenRouter.");
        }

        return $availableModels->first()->id;
    }

    /**
     * Create a new conversation for the authenticated user.
     *
     * @param ConversationData $data
     * @return Conversation
     */
    public function createConversation(ConversationData $data): Conversation
    {
        $user = auth()->user();

        return Conversation::create([
            'user_id' => $user->id,
            'model_id' => $data->model,
            'persona_id' => $data->persona,
            'last_message_at' => now(),
        ]);
    }

    public function createMessage(Conversation $conversation, MessageData $data): Message
    {
        $message = $conversation->messages()->create([
            'role' => $data->role,
            'content' => $data->content,
            'parent_message_id' => $conversation->current_message_id,
        ]);

        $conversation->update([
            'current_message_id' => $message->id,
            'last_message_at' => now(),
        ]);

        return $message;
    }

    public function createStreamedResponse(Conversation $conversation, Message $assistantMessage): \Generator
    {
        $lastSavedAt = microtime(true);
        $saveInterval = 2.0; // seconds

        try {
            yield [
                'type' => 'created',
                'data' => ['message' => $assistantMessage]
            ];

            $request = new ChatRequest(
                messages: [
                    SystemPromptService::persona($conversation->persona_id),
                    SystemPromptService::userInstructions($conversation->user),
                    ...$conversation->contextMessages($assistantMessage->parent_message_id),
                ],
                model: $conversation->model_id,
            );

            $stream = OpenRouter::chat($request)->stream();
            $acc = $stream->getAccumulator();

            foreach ($stream as $chunk) {
                yield $chunk->toArray();

                $now = microtime(true);
                if (($now - $lastSavedAt) >= $saveInterval) {
                    $assistantMessage->updateQuietly([
                        'content' => $acc->getContent(),
                        'reasoning' => $acc->getReasoning(),
                    ]);
                }
            }

            $assistantMessage->update([
                'content' => $acc->getContent(),
                'reasoning' => $acc->getReasoning(),
            ]);

            yield [
                'type' => 'completed',
                'data' => ['message' => $assistantMessage]
            ];

            if ($conversation->title === null) {
                GenerateConversationTitle::dispatch($conversation);
            }

        } catch (Throwable $exception) {

            yield [
                'type' => 'error',
                'data' => [
                    'code' => 'STREAM_ERROR',
                    'message' => $exception->getMessage()
                ]
            ];

        }
    }

}
