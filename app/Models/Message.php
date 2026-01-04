<?php

namespace App\Models;

use App\OpenRouter\Chat\ChatMessage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $conversation_id
 * @property string|null $parent_message_id
 * @property string $role
 * @property string $content
 * @property string|null $reasoning
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Message extends Model
{
    use HasUuids;

    protected $fillable = [
        'conversation_id',
        'parent_message_id',
        'role',
        'content',
        'reasoning',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function parentMessage()
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function childMessages()
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    public function toChatMessage(): ChatMessage
    {
        return new ChatMessage(
            role: $this->role,
            content: $this->content,
        );
    }
}
