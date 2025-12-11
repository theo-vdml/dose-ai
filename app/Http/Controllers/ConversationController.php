<?php

namespace App\Http\Controllers;

use App\Http\Data\QuickPrompt\CompletionRequest;
use App\Models\Conversation;
use App\Services\Conversation as ConversationService;
use App\Services\ConversationTitle;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations;

        return Inertia::render('conversation/Index', [
            'conversations' => $conversations,
        ]);
    }

    public function create(ConversationService $service)
    {
        $models = $service->listModels();
        $selectedModel = $service->getDefaultModel();

        return Inertia::render('conversation/Create', compact('models', 'selectedModel'));
    }


    public function store(CompletionRequest $request, ConversationService $service, ConversationTitle $titleService)
    {
        $title = $titleService->fromMessage($request->message, $request->model);

        $conversation = Conversation::create([
            'model_id' => $request->model,
            'user_id' => Auth::user()->id,
            'title' => $title,
        ]);

        return $conversation;
    }

    public function show($id) {}
}
