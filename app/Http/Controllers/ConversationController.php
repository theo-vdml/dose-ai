<?php

namespace App\Http\Controllers;

use App\Services\ConversationService;
use App\Http\Data\{ConversationData, MessageData};
use App\Jobs\GenerateConversationTitle;
use App\Models\Conversation;
use App\Models\Message;
use App\OpenRouter\Chat\ChatRequest;
use App\OpenRouter\Facades\OpenRouter;
use App\OpenRouter\Stream\StreamIterator;
use App\Services\SSEEmitterService;
use App\Services\SystemPromptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\{Inertia, Response};
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService,
    ){}

    public function index(): Response
    {
        $conversations = Auth::user()->conversations;

        return Inertia::render('conversation/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function create(): Response
    {
        $models = $this->conversationService->getAvailableModels();
        $selectedModel = $this->conversationService->getDefaultModelId();
        $personas = config('personas.list');

        return Inertia::render('conversation/Create', compact('models', 'selectedModel', 'personas'));
    }

    public function store(ConversationData $data): RedirectResponse
    {
        $conversation = $this->conversationService
            ->createConversation($data);

        return redirect()->route('conversations.show', [
            'conversation' => $conversation->id,
            'submit' => true,
        ])->with('messageValue', $data->message);
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
        $message = $this->conversationService
            ->createMessage($conversation, $messageData);

        return response()->json($message);
    }

    public function stream(Conversation $conversation): StreamedResponse | string
    {
        $assistantMessage = $this->conversationService->createMessage($conversation, new MessageData(
            role: 'assistant',
            content: ''
        ));

        $stream = $this->conversationService
            ->createStreamedResponse($conversation, $assistantMessage);

        if (Cache::get('disable_streaming')) {
            return $this->fakeStream($stream);
        }

        return response()->stream(function () use ($stream) {
            foreach ($stream as $chunk) {
                SSEEmitterService::emitJson($chunk);
            }
            SSEEmitterService::done();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function fakeStream(\Generator $stream)
    {
        $body = '';
        foreach ($stream as $chunk) {
            $body .= SSEEmitterService::formatData(json_encode($chunk));
        }
        $body .= SSEEmitterService::formatData('[DONE]');
        return $body;
    }
}
