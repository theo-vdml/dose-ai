<?php

namespace App\OpenRouter\Models;

class ModelInfo
{
    public function __construct(
        protected array $data,
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
