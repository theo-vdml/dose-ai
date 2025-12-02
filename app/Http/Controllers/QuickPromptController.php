<?php

namespace App\Http\Controllers;;

use App\Http\Data\QuickPrompt\CompletionRequest;
use App\Services\QuickPrompt;
use Inertia\Inertia;

class QuickPromptController extends Controller
{
    public function __construct(
        private QuickPrompt $quickPromptService,
    ) {}

    public function index()
    {
        $models = $this->quickPromptService->listModels();
        $selectedModel = $this->quickPromptService->getDefaultModel();

        // dd($models);

        return Inertia::render('QuickPrompt/Index', [
            'models' => $models,
            'selectedModel' => $selectedModel,
        ]);
    }

    public function completion(CompletionRequest $request)
    {
        $response = null;
        $error = null;

        try {
            $response = $this->quickPromptService->sendMessage(
                $request->message,
                $request->model,
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $models = $this->quickPromptService->listModels();

        return Inertia::render('QuickPrompt/Index', [
            'models' => $models,
            'selectedModel' => $request->model,
            'message' => $request->message,
            'response' => $response,
            'error' => $error,
        ]);
    }
}
