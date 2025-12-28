<?php

namespace App\Services\OpenRouter\Data\Entities;

use Spatie\LaravelData\Data;

class Message extends Data
{
    public function __construct(
        public string $role,
        public string $content,
        public ?string $reasoning = null,
    ) {}

    public static function user(string $content): self
    {
        return new self('user', $content);
    }

    public static function assistant(string $content): self
    {
        return new self('assistant', $content);
    }

    public static function system(string $content): self
    {
        return new self('system', $content);
    }
}
