<?php

namespace App\Services;

class SSEEmitterService
{
    public static function formatData(string $data)
    {
        return 'data: ' . $data . "\n\n";
    }

    public static function emit(string $data)
    {
        echo static::formatData($data);
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
        echo static::formatData('[DONE]');
        ob_flush();
        flush();
    }
}
