<?php

namespace App\Http\Controllers;

use App\Http\Data\StoreMessageRequest;
use Inertia\{Inertia, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Conversation;
use App\Http\Data\{MessageData, NewChatRequest, StreamRequest};
use App\Services\OpenRouter\Facades\OpenRouter;
use App\Services\OpenRouter\Data\Stream\AssistantMessageCreatedChunk;
use App\Services\OpenRouter\Data\Stream\MessagePersistedChunk;
use App\Services\OpenRouter\Data\Stream\UserMessageCreatedChunk;
use App\Services\OpenRouter\StreamAccumulator;
use App\Services\OpenRouter\StreamEmitter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $models = OpenRouter::models()->all();
        $selectedModel = OpenRouter::models()->default();

        return Inertia::render('conversation/Create', compact('models', 'selectedModel'));
    }

    public function store(NewChatRequest $request): RedirectResponse
    {
        $conversation = Conversation::create([
            'model_id' => $request->model,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('conversations.show', [
            'conversation' => $conversation->id,
            'q' => base64_encode($request->message),
            'submit' => true,
        ]);
    }

    public function show(Conversation $conversation): Response
    {
        $models = OpenRouter::models()->all();
        $conversation->load('messages');

        return Inertia::render('conversation/Show', [
            'conversation' => $conversation,
            'models' => $models,
            'model_id' => $conversation->model_id,
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
        Log::debug('Starting stream for conversation ID: ' . $conversation->id);

        Log::debug('Message Data: ' . json_encode($messageData));

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

        $messagesChain = $conversation->activeMessageChain(
            includeReasoning: false,
            startsFrom: $userMessage->id
        );

        // Start the OpenRouter streaming completion from the user message
        $stream = OpenRouter::completion()
            ->model($conversation->model_id)
            ->messages($messagesChain->all())
            ->maxTokens(0)
            ->stream();

        return response()->stream(function () use ($stream, $assistantMessage): void {

            foreach ($stream as $chunk) {
                StreamEmitter::chunk($chunk);
            }

            /** @var StreamAccumulator $acc */
            $acc = $stream->getAccumulator();

            $assistantMessage->update([
                'content' => $acc->getContent(),
                'reasoning' => $acc->getReasoning(),
            ]);

            StreamEmitter::chunk(new MessagePersistedChunk(message: $assistantMessage));
            StreamEmitter::done();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
