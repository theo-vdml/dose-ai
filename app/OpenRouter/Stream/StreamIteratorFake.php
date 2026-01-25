<?php

namespace App\OpenRouter\Stream;

use App\OpenRouter\Stream\StreamIterator;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Illuminate\Http\Client\Response;

class StreamIteratorFake extends StreamIterator
{
    public function __construct(protected array $queue)
    {
        $response = new Response(new PsrResponse(200, [], ''));
        parent::__construct($response);
    }

    protected function parseStream(): \Generator
    {
        foreach ($this->queue as $data) {
            $chunks = StreamChunkFactory::from($data);
            $this->accumulator->add($chunks);
            foreach ($chunks as $chunk) {
                yield $chunk;
            }
        }
    }
}
