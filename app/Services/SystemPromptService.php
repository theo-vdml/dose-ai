<?php

namespace App\Services;

use App\Models\User;
use App\OpenRouter\Chat\ChatMessage;
use Illuminate\Support\Facades\View;

class SystemPromptService
{
    public static function userInstructions(User $user): ChatMessage
    {
        return ChatMessage::system($user->preferences->instruction_prompt ?? '');
    }

    public static function persona(?string $personaId): ChatMessage
    {
        if (! $personaId) {
            return ChatMessage::system('');
        }

        if (! View::exists("prompts.personas.{$personaId}")) {
            return ChatMessage::system('');
        }

        $personaContent = View::make("prompts.personas.{$personaId}")->render();
        return ChatMessage::system($personaContent);
    }
}
