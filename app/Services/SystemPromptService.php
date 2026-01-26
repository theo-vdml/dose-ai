<?php

namespace App\Services;

use App\Models\User;
use App\OpenRouter\Chat\ChatMessage;
use Illuminate\Support\Facades\View;

class SystemPromptService
{
    public static function mainSystemPrompt(User $user): ChatMessage
    {
        $systemPromptContent = View::make('prompts.system', [
            'username' => $user->name,
            'date' => now()->toFormattedDateString(),
            'time' => now()->toTimeString(),
            'timezone' => now()->getTimezone()->getName(),
        ])->render();

        return ChatMessage::system($systemPromptContent);
    }

    public static function userInstructions(User $user): ChatMessage
    {
        $userPromptContent = View::make('prompts.user', [
            'username' => $user->name,
            'user_custom_instructions' => $user->preferences->instruction_prompt ?? '',
        ])->render();

        return ChatMessage::system($userPromptContent);
    }

    public static function persona(?string $personaId): ChatMessage
    {
        $safePersonaId = basename($personaId);

        if (! $safePersonaId || ! View::exists("prompts.personas.{$safePersonaId}")) {
            return ChatMessage::system('There is no persona set for this chat session.');
        }

        $personaPromptContent = View::make("prompts.personas.{$safePersonaId}")
            ->render();

        return ChatMessage::system($personaPromptContent);
    }
}
