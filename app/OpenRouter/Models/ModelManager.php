<?php

namespace App\OpenRouter\Models;

use App\OpenRouter\DTO\ModelData;
use App\OpenRouter\OpenRouterClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class ModelManager
{
    /**
     * Endpoint for retrieving models from the OpenRouter API.
     */
    const string MODELS_ENDPOINT = '/models';

    /**
     * In-memory cache of the fetched models.
     *
     * @var Collection<int, ModelData>|null
     */
    private ?Collection $cachedModels = null;

    /**
     * Create a new ModelManager instance.
     *
     * @param OpenRouterClient $client
     */
    public function __construct(
        protected OpenRouterClient $client
    ) {}

    /**
     * Retrieve the list of available models.
     *
     * This method utilizes an in-memory cache to prevent redundant API calls
     * within the same request lifecycle.
     *
     * @return Collection<int, ModelData>
     * @throws ConnectionException
     * @throws RequestException
     */
    public function list(): Collection
    {
        if ($this->cachedModels === null)
        {
            $this->cachedModels = $this->fetchModels();
        }

        return $this->cachedModels;
    }

    /**
     * Find a specific model by its unique identifier.
     *
     * @param string $id
     * @return ModelData|null
     * @throws ConnectionException
     * @throws RequestException
     */
    public function find(string $id): ?ModelData
    {
        return $this->list()
            ->firstWhere('id', $id);
    }

    /**
     * Fetch the latest models directly from the OpenRouter API.
     *
     * @return Collection<int, ModelData>
     * @throws ConnectionException
     * @throws RequestException
     */
    protected function fetchModels(): Collection
    {
        $response = $this->client
            ->http()
            ->get(self::MODELS_ENDPOINT)
            ->throw()
            ->json();

        return ModelData::collect($response['data'], Collection::class);
    }
}
