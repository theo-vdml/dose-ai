<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'application_theme',
        'last_used_model',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
