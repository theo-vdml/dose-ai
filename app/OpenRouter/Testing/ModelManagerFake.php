<?php

namespace App\OpenRouter\Testing;

use App\OpenRouter\DTO\ModelData;
use App\OpenRouter\Models\ModelManager;
use App\OpenRouter\OpenRouterClient;
use Exception;
use Illuminate\Support\Collection;

class ModelManagerFake extends ModelManager
{
    protected array $models = [];
    protected Exception $exception;

    public function __construct(OpenRouterClient $client)
    {
        parent::__construct($client);
    }

    /**
     * Set the models to be returned when fetching models.
     * @param array<int, ModelData> $models
     * @return static
     */
    public function withModels(array $models): static
    {
        $this->models = $models;
        return $this;
    }

    /**
     * Set an exception to be thrown when fetching models.
     * @param Exception $exception
     * @return static
     */
    public function shouldThrow(Exception $exception): static
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Mock fetching models from the OpenRouter API.
     * Returns fake models or throws an exception if set.
     * @throws Exception
     * @return Collection<int, ModelData>
     */
    public function fetchModels(): Collection
    {
        if (isset($this->exception)) {
            throw $this->exception;
        }

        if (empty($this->models)) {
            return Collection::times(3, fn() => ModelData::fake());
        }

        return ModelData::collect($this->models, Collection::class);
    }
}
