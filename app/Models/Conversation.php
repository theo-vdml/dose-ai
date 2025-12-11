<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'model_id',
        'forked_from_conversation_id',
        'last_message_at',
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

    public function forkedFromConversation()
    {
        return $this->belongsTo(Conversation::class, 'forked_from_conversation_id');
    }

    public function forkedConversations()
    {
        return $this->hasMany(Conversation::class, 'forked_from_conversation_id');
    }
}
