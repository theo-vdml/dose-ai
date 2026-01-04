<?php

namespace App\OpenRouter\Models;

class ModelList
{
    protected array $models;

    public function __construct(array $models)
    {
        $this->models = array_map(
            fn(array $modelData) => new ModelInfo($modelData),
            $models
        );
    }

    /**
     * @return ModelInfo[]
     */
    public function all(): array
    {
        return $this->models;
    }

    public function get(string $modelId): ?ModelInfo
    {
        foreach ($this->models as $model) {
            if ($model->get('id') === $modelId) {
                return $model;
            }
        }

        return null;
    }

    public function has(string $modelId): bool
    {
        return $this->get($modelId) !== null;
    }

    public function toArray(): array
    {
        return array_map(
            fn(ModelInfo $model) => $model->toArray(),
            $this->models
        );
    }
}
