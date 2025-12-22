<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Conversation.{id}', function () {
    return Auth::check();
});
