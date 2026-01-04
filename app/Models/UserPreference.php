<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'last_used_model',
        'instruction_prompt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
