<?php

namespace App\Http\Controllers;

use App\OpenRouter\Chat\ChatRequest;
use Exception;
use Inertia\{Inertia, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Conversation;
use App\Http\Data\{MessageData, ChatData};
use App\Jobs\GenerateConversationTitle;
use App\OpenRouter\Facades\OpenRouter;
use App\OpenRouter\Stream\StreamAccumulator;
use App\OpenRouter\Stream\StreamChunk;
use App\Services\SSEEmitterService;
use App\Services\SystemPromptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    public function index(): Response
    {
        $conversations = Auth::user()->conversations;

        return Inertia::render('conversation/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function create(): Response
    {
        $models = OpenRouter::models()->list()->toArray();
        $selectedModel = OpenRouter::models()->defaultId();
        $personas = config('personas.list');

        return Inertia::render('conversation/Create', compact('models', 'selectedModel', 'personas'));
    }

    public function store(ChatData $chat): RedirectResponse
    {
        $conversation = Conversation::create([
            'model_id' => $chat->model,
            'user_id' => Auth::user()->id,
            'persona_id' => $chat->persona,
            'last_message_at' => now(),
        ]);

        return redirect()->route('conversations.show', [
            'conversation' => $conversation->id,
            'submit' => true,
        ])->with('messageValue', $chat->message);
    }

    public function show(Conversation $conversation): Response
    {
        $conversation->load('messages');

        return Inertia::render('conversation/Show', [
            'conversation' => $conversation,
            'model_id' => $conversation->model_id,
            'messageValue' => session('messageValue'),
        ]);
    }

    public function storeMessage(Conversation $conversation, MessageData $messageData): JsonResponse
    {
        // Create new message associated with the conversation
        $message = $conversation->messages()->create([
            'role' => $messageData->role,
            'content' => $messageData->content,
            'parent_message_id' => $conversation->current_message_id,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'current_message_id' => $message->id,
        ]);

        return response()->json($message);
    }

    public function stream(Conversation $conversation): StreamedResponse
    {

        $assistantMessage = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => '',
            'parent_message_id' => $conversation->current_message_id,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'current_message_id' => $assistantMessage->id,
        ]);

        // Retrieve the parent user message
        $userMessage = $assistantMessage->parentMessage;

        $request = new ChatRequest(
            model: $conversation->model_id,
            messages: [
                SystemPromptService::persona($conversation->persona_id),
                SystemPromptService::userInstructions($conversation->user),
                ...$conversation->contextMessages($userMessage?->id),
            ],
        );

        Log::info('Chat Request', [
            'request' => $request,
        ]);

        $stream = OpenRouter::chat($request)->stream();

        return response()->stream(function () use ($stream, $assistantMessage, $conversation): void {

            try {
                // Inital emit of the created assistant message to get the ID to the client
                SSEEmitterService::emitJson([
                    'type' => 'created',
                    'data' => [
                        'message' => $assistantMessage,
                    ]
                ]);

                $acc = $stream->getAccumulator();

                $lastSavedAt = microtime(true);
                $saveInterval = 5.0; // seconds

                foreach ($stream as $chunk) {
                    // Accumulate content and reasoning
                    SSEEmitterService::emitJson($chunk->toArray());

                    // Update the database instance every few seconds to ensure progress is saved
                    $now = microtime(true);
                    if (($now - $lastSavedAt) >= $saveInterval) {
                        $assistantMessage->updateQuietly([
                            'content' => $acc->getContent(),
                            'reasoning' => $acc->getReasoning(),
                        ]);
                        $lastSavedAt = $now;
                        throw new Exception('Le streaming a été interrompu pour des raisons de test.');
                    }
                }

                // Final update to ensure all content is saved
                $assistantMessage->update([
                    'content' => $acc->getContent(),
                    'reasoning' => $acc->getReasoning(),
                ]);

                // Emit completion event
                SSEEmitterService::emitJson([
                    'type' => 'completed',
                    'data' => [
                        'message' => $assistantMessage,
                    ],
                ]);

                // Emit done event to close the stream
                SSEEmitterService::done();

                // Dispatch job to generate conversation title if it's missing
                if ($conversation->title === null) {
                    GenerateConversationTitle::dispatch($conversation);
                }
            } catch (\Throwable $e) {
                Log::error('Error during streaming', [
                    'error' => $e->getMessage(),
                    'conversation_id' => $conversation->id,
                    'message_id' => $assistantMessage->id,
                ]);

                // Emit error event
                SSEEmitterService::emitJson([
                    'type' => 'error',
                    'data' => [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                    ],
                ]);

                // Emit done event to close the stream
                SSEEmitterService::done();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
