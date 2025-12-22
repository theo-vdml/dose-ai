<?php

namespace App\Http\Controllers;

use Inertia\{Inertia, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Conversation;
use App\Http\Data\{NewChatRequest, StreamRequest};
use App\Services\OpenRouter\Facades\OpenRouter;
use App\Services\OpenRouter\Data\Entities\Message;
use App\Services\OpenRouter\Data\Stream\AssistantMessageCreatedChunk;
use App\Services\OpenRouter\Data\Stream\UserMessageCreatedChunk;
use App\Services\OpenRouter\StreamEmitter;
use Illuminate\Http\Request;

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

        Session::flash('initialMessage', $request->message);

        return redirect()->route('conversations.show', $conversation->id);
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

    public function showAlt(Conversation $conversation): Response
    {
        $models = OpenRouter::models()->all();
        $conversation->load('messages');

        return Inertia::render('conversation/ShowAlt', [
            'conversation' => $conversation,
            'models' => $models,
            'model_id' => $conversation->model_id,
        ]);
    }

    public function stream(Conversation $conversation, StreamRequest $request, Request $r): StreamedResponse
    {
        $userMessageData = Message::user($request->message);
        $model = $request->model ?? $conversation->model_id;

        $userMessage = $conversation->messages()->create($userMessageData->toArray());

        $stream = OpenRouter::completion()
            ->model($model)
            ->messages([$userMessageData])
            ->maxTokens(0)
            ->stream();

        return response()->stream(function () use ($stream, $conversation, $userMessage): void {

            StreamEmitter::chunk(new UserMessageCreatedChunk(message: $userMessage));

            foreach ($stream as $chunk) {
                StreamEmitter::chunk($chunk);
            }

            $acc = $stream->getAccumulator();
            $assistantMessageData = Message::assistant($acc->getContent());
            $assistantMessage = $conversation->messages()->create([
                ...$assistantMessageData->toArray(),
                'parent_message_id' => $userMessage->id,
            ]);

            StreamEmitter::chunk(new AssistantMessageCreatedChunk(message: $assistantMessage));
            StreamEmitter::done();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
