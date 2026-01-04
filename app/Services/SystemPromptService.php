<?php

namespace App\Services;

use App\Services\OpenRouter\Data\Entities\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class SystemPromptService
{

    public function prepend(array $messages)
    {
        $content = $this->render();



        Log::info('System prompt generated:', ['prompt' => $content]);

        if ($content) {
            $systemPrompt = Message::system($content);

            array_unshift($messages, $systemPrompt);
            Log::info('System prompt prepended to messages.');
        }

        return $messages;
    }

    public function render()
    {
        $view = 'prompts.system';

        if (! View::exists($view)) {
            return null;
        }

        return trim(View::make($view)->render());
    }
}
