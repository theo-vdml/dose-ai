<?php

namespace App\Http\Data;

use App\Models\Message;
use Spatie\LaravelData\Data;

class StoreMessageRequest extends Data
{
    public function __construct(
        public Message $message,
    ) {}
}
