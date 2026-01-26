<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => null,
            'model_id' => null,
            'last_message_at' => null,
            'current_message_id' => null,
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes): array => [
            'user_id' => $user->id,
        ]);
    }

    public function withTitle(?string $title = null): static
    {
        return $this->state(fn(array $attributes): array => [
            'title' => $title ?? $this->faker->sentence(),
        ]);
    }

    public function withMessages(int $count): static
    {
        return $this->afterCreating(function (Conversation $conversation) use ($count) {

            $parentMessageId = null;

            for ($i = 0; $i < $count; $i++) {

                $role = $i % 2 === 0 ? 'user' : 'assistant';
                $message = $conversation->messages()->create([
                    'role' => $role,
                    'content' => $this->faker->paragraph(),
                    'parent_message_id' => $parentMessageId,
                    'created_at' => now()->subMinutes($count - $i),
                ]);

                $parentMessageId = $message->id;
            }

            if ($message) {
                $conversation->update([
                    'last_message_at' => $message->created_at,
                    'current_message_id' => $message->id,
                ]);
            }
        });
    }
}
