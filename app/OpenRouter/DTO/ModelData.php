<?php

namespace App\OpenRouter\DTO;

use App\OpenRouter\Enums\Parameter;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * Data Transfer Object representing an OpenRouter AI Model.
 * * This DTO handles the normalization of model data coming from the API
 * and prepares it for usage within the application or TypeScript frontend.
 */
#[TypeScript]
class ModelData extends Data
{
    public function __construct(
        /** Unique identifier for the model */
        public string $id,

        /** Canonical slug for the model */
        public string $canonical_slug,

        /** Display name of the model */
        public string $name,

        /** Unix timestamp of when the model was created */
        public int $created,

        /** Pricing information for the model */
        public PricingData $pricing,

        /** Maximum context length in tokens */
        public ?int $context_length = null,

        /** Model architecture information */
        public ArchitectureData $architecture,

        /** Information about the top provider for this model */
        public TopProviderData $top_provider,

        /** Per-request token limits */
        public ?PerRequestLimitsData $per_request_limits = null,

        /**
         * List of supported parameters for this model
         * @var array<int, Parameter>
         */
        public array $supported_parameters = [],

        /** Default parameters for this model */
        public ?DefaultParametersData $default_parameters = null,

        /** Hugging Face model identifier, if applicable*/
        public ?string $hugging_face_id = null,

        /** Description of the model */
        public ?string $description = null,
    ){}

    /**
     * Generate a fake model instance for testing or seeding purposes.
     * This factory method ensures all DTO constraints are met with
     * realistic, randomized data.
     *
     * @param string|null $id The unique model identifier.
     * @param string|null $name Optional name override.
     * @return self
     */
    public static function fake(?string $id = null, ?string $name = null): self
    {
        $id = $id ?? 'fake-model-' . Str::random(8);
        $contextLength = rand(4096, 128000);
        $maxCompletionTokens = rand(256, 4096);

        return new self(
            id: $id,
            canonical_slug: $id,
            name: $name ?? Str::of($id)->replace(['-', '_', '/'], ' ')->title()->toString(),
            created: time(),
            pricing: PricingData::from([
                'prompt' => number_format(rand(1, 50) / 1_000_000, 7, '.', ''),
                'completion' => number_format(rand(1, 100) / 1_000_000, 7, '.', ''),
            ]),
            context_length: $contextLength,
            architecture: ArchitectureData::from([
                'modality' => 'text->text',
                'input_modalities' => ['text'],
                'output_modalities' => ['text'],
            ]),
            top_provider: TopProviderData::from([
                'is_moderated' => (bool) rand(0, 1),
                'context_length' => $contextLength,
                'max_completion_tokens' => $maxCompletionTokens,
            ]),
            per_request_limits: PerRequestLimitsData::from([
                'prompt_tokens' => $contextLength,
                'completion_tokens' => $maxCompletionTokens,
            ]),
            supported_parameters: [
                Parameter::TEMPERATURE,
                Parameter::TOP_P,
                Parameter::FREQUENCY_PENALTY,
            ],
            default_parameters: DefaultParametersData::from([
                'temperature' => 0.7,
                'top_p' => 1.0,
                'frequency_penalty' => 0.0,
            ]),
            hugging_face_id: $id . '-hf',
            description: "A fake description for the model $id.",
        );
    }
}
