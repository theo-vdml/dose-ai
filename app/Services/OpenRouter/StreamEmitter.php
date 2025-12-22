<?php

namespace App\Services\OpenRouter;

use App\Services\OpenRouter\Data\Stream\BaseChunk;

class StreamEmitter
{
    /**
     * Emit a Server-Sent Event and flush output buffers.
     *
     * @param array<string, mixed> $data
     */
    public static function emit(array $data): void
    {
        echo 'data: ' . json_encode($data) . "\n\n";
        ob_flush();
        flush();
    }

    /**
     * Emit the [DONE] signal to close the stream.
     */
    public static function done(): void
    {
        echo "data: [DONE]\n\n";
        ob_flush();
        flush();
    }

    /**
     * Emit a BaseChunk using its toFrontend() method.
     */
    public static function chunk(BaseChunk $chunk): void
    {
        self::emit($chunk->toFrontend());
    }
}
