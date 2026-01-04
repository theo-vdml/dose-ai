<?php

namespace App\Models;

use App\OpenRouter\Chat\ChatMessage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property int $user_id
 * @property string|null $title
 * @property string $model_id
 * @property string|null $persona_id
 * @property \Illuminate\Support\Carbon|null $last_message_at
 * @property string|null $current_message_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Conversation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'title',
        'model_id',
        'persona_id',
        'last_message_at',
        'current_message_id',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function currentMessage()
    {
        return $this->belongsTo(Message::class, 'current_message_id');
    }

    /**
     * Summary of contextMessages
     * @param ?string $startsFrom
     * @return array<ChatMessage>
     */
    public function contextMessages(?string $startsFrom = null): array
    {
        $messages = collect();

        $current = $startsFrom ? $this->messages()->find($startsFrom) : $this->currentMessage;

        while ($current) {
            $messages->prepend($current->toChatMessage());
            $current = $current->parentMessage;
        }

        return $messages->values()->toArray();
    }
}
