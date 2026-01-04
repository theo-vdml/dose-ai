<?php

namespace App\Services;

class SSEEmitterService
{
    public static function emit(string $data)
    {
        echo 'data: ' . $data . "\n\n";
        ob_flush();
        flush();
    }

    public static function emitJson(array $data)
    {
        self::emit(json_encode($data));
    }

    public static function comment(string $comment): void
    {
        echo ":: {$comment}\n\n";
        ob_flush();
        flush();
    }

    public static function done(): void
    {
        echo "data: [DONE]\n\n";
        ob_flush();
        flush();
    }
}
