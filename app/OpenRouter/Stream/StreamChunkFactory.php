<?php

namespace App\OpenRouter\Stream;

class StreamChunkFactory
{
    public static function from(array $data)
    {
        // Accumulator for chunks to return
        $chunks = [];

        // Validate presence of choices
        if (!isset($data['choices'][0])) {
            return $chunks;
        }

        // Extract the first choice
        $choice = $data['choices'][0];

        // Validate choice structure
        if (!$choice || $choice === null || !is_array($choice)) {
            return $chunks;
        }

        // Extract delta from choice
        $delta = $choice['delta'] ?? [];

        // Create chunk for reasoning if present
        if (isset($delta['reasoning'])) {
            $reasoning = $delta['reasoning'];
            if (\is_string($reasoning) && $reasoning !== '') {
                $chunks[] = StreamChunk::reasoning(
                    delta: $reasoning
                );
            }
        }

        // Create chunk for content if present
        if (isset($delta['content'])) {
            $content = $delta['content'];
            if (\is_string($content) && $content !== '') {
                $chunks[] = StreamChunk::content(
                    delta: $content
                );
            }
        }

        // Create chunk for finish reason if present
        if (isset($choice['finish_reason'])) {
            $reason = $choice['finish_reason'];
            if (\is_string($reason) && $reason !== '') {
                $chunks[] = StreamChunk::finishReason(
                    router: $reason,
                    model: $choice['native_finish_reason'] ?? ''
                );
            }
        }

        // Create usage chunk if present
        if (isset($data['usage'])) {
            $chunks[] = StreamChunk::usage(
                promptTokens: $data['usage']['prompt_tokens'] ?? 0,
                completionTokens: $data['usage']['completion_tokens'] ?? 0,
                totalTokens: $data['usage']['total_tokens'] ?? 0,
            );
        }

        // Return the array of created chunks
        return $chunks;
    }
}
