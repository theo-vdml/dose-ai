<?php

namespace App\Services\OpenRouter\Data;

use Spatie\LaravelData\Data;

class Message extends Data
{
    public function __construct(
        public string $role,
        public string $content,
    ) {}

    public static function user(string $content): self
    {
        return new self(role: 'user', content: $content);
    }

    public static function assistant(string $content): self
    {
        return new self(role: 'assistant', content: $content);
    }

    public static function system(string $content): self
    {
        return new self(role: 'system', content: $content);
    }
}
