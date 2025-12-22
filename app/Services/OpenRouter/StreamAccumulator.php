<?php

namespace App\Services\OpenRouter;

use App\Services\OpenRouter\Data\Stream\BaseChunk;
use App\Services\OpenRouter\Data\Stream\ContentChunk;
use App\Services\OpenRouter\Data\Stream\FinishReasonChunk;
use App\Services\OpenRouter\Data\Stream\ReasoningChunk;
use App\Services\OpenRouter\Data\Stream\UsageChunk;

class StreamAccumulator
{
    protected string $content = '';
    protected string $reasoning = '';
    protected ?string $finishReason = null;
    protected ?string $finishReasonNative = null;
    protected ?array $usage = null;

    public function add(BaseChunk|array $chunk): void
    {
        if (\is_array($chunk)) {
            foreach ($chunk as $singleChunk) {
                if ($singleChunk instanceof BaseChunk) {
                    $this->add($singleChunk);
                }
            }
            return;
        }

        match (true) {
            $chunk instanceof ContentChunk => $this->content .= $chunk->delta,
            $chunk instanceof ReasoningChunk => $this->reasoning .= $chunk->delta,
            $chunk instanceof FinishReasonChunk => $this->setFinishReason($chunk),
            $chunk instanceof UsageChunk => $this->setUsage($chunk),
            default => null,
        };

        return;
    }

    protected function setFinishReason(FinishReasonChunk $chunk): void
    {
        $this->finishReason = $chunk->router;
        $this->finishReasonNative = $chunk->model;
    }

    protected function setUsage(UsageChunk $chunk): void
    {
        $this->usage = [
            'prompt_tokens' => $chunk->promptTokens,
            'completion_tokens' => $chunk->completionTokens,
            'total_tokens' => $chunk->totalTokens,
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
