<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'parent_message_id',
        'role',
        'content'
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
}
