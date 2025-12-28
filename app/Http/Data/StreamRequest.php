<?php

namespace App\Http\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;

class StreamRequest extends Data
{
    public function __construct(
        public string $message,
    ) {}
}
