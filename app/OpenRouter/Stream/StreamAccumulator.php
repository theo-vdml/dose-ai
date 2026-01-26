<?php

namespace App\OpenRouter\Stream;

class StreamAccumulator
{
    protected string $content = '';

    protected string $reasoning = '';

    protected ?string $finishReason = null;

    protected ?string $finishReasonNative = null;

    protected ?array $usage = null;

    public function add(StreamChunk|array $chunk): void
    {
        if (\is_array($chunk)) {
            foreach ($chunk as $singleChunk) {
                if ($singleChunk instanceof StreamChunk) {
                    $this->add($singleChunk);
                }
            }

            return;
        }

        match (true) {
            $chunk->type() === 'content' => $this->content .= $chunk->get('delta', ''),
            $chunk->type() === 'reasoning' => $this->reasoning .= $chunk->get('delta', ''),
            $chunk->type() === 'finish_reason' => $this->setFinishReason($chunk),
            $chunk->type() === 'usage' => $this->setUsage($chunk),
            default => null,
        };

    }

    protected function setFinishReason(StreamChunk $chunk): void
    {
        $this->finishReason = $chunk->get('router', null);
        $this->finishReasonNative = $chunk->get('model', null);
    }

    protected function setUsage(StreamChunk $chunk): void
    {
        $this->usage = [
            'prompt_tokens' => $chunk->get('prompt_tokens', 0),
            'completion_tokens' => $chunk->get('completion_tokens', 0),
            'total_tokens' => $chunk->get('total_tokens', 0),
        ];
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getReasoning(): string
    {
        return $this->reasoning;
    }

    public function getFinishReason(): ?string
    {
        return $this->finishReason;
    }

    public function getFinishReasonNative(): ?string
    {
        return $this->finishReasonNative;
    }

    public function getUsage(): ?array
    {
        return $this->usage;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->getContent(),
            'reasoning' => $this->getReasoning(),
            'finish_reason' => $this->getFinishReason(),
            'finish_reason_native' => $this->getFinishReasonNative(),
            'usage' => $this->getUsage(),
        ];
    }

    public function reset(): void
    {
        $this->content = '';
        $this->reasoning = '';
        $this->finishReason = null;
        $this->finishReasonNative = null;
        $this->usage = null;
    }
}
