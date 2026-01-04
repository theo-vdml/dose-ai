<?php

namespace App\Http\Controllers;

use App\OpenRouter\Chat\ChatRequest;
use Inertia\{Inertia, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Conversation;
use App\Http\Data\{MessageData, ChatData};
use App\Jobs\GenerateConversationTitle;
use App\OpenRouter\Chat\ChatMessage;
use App\OpenRouter\Facades\OpenRouter;
use App\OpenRouter\Stream\StreamAccumulator;
use App\OpenRouter\Stream\StreamChunk;
use App\Services\ConversationContextBuilder;
use App\Services\SSEEmitterService;
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

        return Inertia::render('conversation/Create', compact('models', 'selectedModel'));
    }

    public function store(ChatData $chat): RedirectResponse
    {
        $conversation = Conversation::create([
            'model_id' => $chat->model,
            'user_id' => Auth::user()->id,
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
            'parent_message_id' => $messageData->parent_message_id,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'current_message_id' => $message->id,
        ]);

        return response()->json($message);
    }

    public function stream(Conversation $conversation, MessageData $messageData): StreamedResponse
    {
        $assistantMessage = $conversation->messages()->create([
            'role' => $messageData->role,
            'content' => $messageData->content ?? '',
            'parent_message_id' => $messageData->parent_message_id,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'current_message_id' => $assistantMessage->id,
        ]);

        // Retrieve the parent user message
        $userMessage = $assistantMessage->parentMessage;

        // $messages = $conversation->contextMessages($userMessage?->id);
        $messages = ConversationContextBuilder::make(
            $conversation,
            startsFrom: $userMessage?->id,
            beforeMessages: [
                ChatMessage::system($conversation->user->preferences->instruction_prompt),
            ],
        );

        Log::debug('Context Built for Streaming', ['messages' => $messages]);

        $request = new ChatRequest(
            model: $conversation->model_id,
            messages: $messages,
        );

        $stream = OpenRouter::chat($request)->stream();

        return response()->stream(function () use ($stream, $assistantMessage, $conversation): void {

            /** @var StreamChunk $chunk */
            foreach ($stream as $chunk) {
                SSEEmitterService::emitJson($chunk->toArray());
            }

            /** @var StreamAccumulator $acc */
            $acc = $stream->getAccumulator();

            $assistantMessage->update([
                'content' => $acc->getContent(),
                'reasoning' => $acc->getReasoning(),
            ]);

            SSEEmitterService::emitJson([
                'type' => 'message_persisted',
                'data' => [
                    'message' => $assistantMessage,
                ],
            ]);

            SSEEmitterService::done();

            if ($conversation->title === null) {
                GenerateConversationTitle::dispatch($conversation);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
