<?php

namespace App\OpenRouter\Stream;

use Illuminate\Http\Client\Response;
use IteratorAggregate;
use RuntimeException;
use Traversable;
use Generator;

/**
 * @implements IteratorAggregate<int, StreamChunk>
 */
class StreamIterator implements IteratorAggregate
{
    protected bool $consumed = false;

    public function __construct(
        protected Response $response,
        protected StreamAccumulator $accumulator = new StreamAccumulator(),
    ) {}

    public function getAccumulator(): StreamAccumulator
    {
        return $this->accumulator;
    }

    public function getIterator(): Traversable
    {
        if ($this->consumed) {
            throw new RuntimeException('Stream can only be consumed once.');
        }

        $this->consumed = true;
        return $this->parseStream();
    }

    protected function parseStream(): Generator
    {
        $body = $this->response->getBody();
        $buffer = '';

        while (! $body->eof()) {
            $chunk = $body->read(1024);
            $buffer .= $chunk;

            $lines = explode("\n", $buffer);
            $buffer = array_pop($lines);

            foreach ($lines as $line) {

                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                if (str_starts_with($line, 'data: ')) {

                    $json = substr($line, 6);

                    if ($json === '[DONE]') {
                        return;
                    }

                    $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

                    if ($data && json_last_error() === JSON_ERROR_NONE) {
                        $chunks = StreamChunkFactory::from($data);
                        $this->accumulator->add($chunks);
                        foreach ($chunks as $chunk) {
                            yield $chunk;
                        }
                    }
                }
            }
        }

        if (! empty($buffer) && str_starts_with(trim($buffer), 'data: ')) {
            $json = substr(trim($buffer), 6);
            if ($json !== '[DONE]') {
                $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

                if ($data && json_last_error() === JSON_ERROR_NONE) {
                    $chunks = StreamChunkFactory::from($data);
                    $this->accumulator->add($chunks);
                    foreach ($chunks as $chunk) {
                        yield $chunk;
                    }
                }
            }
        }
    }
}
