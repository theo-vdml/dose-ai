<?php

namespace App\OpenRouter\DTO;

use App\OpenRouter\Enums\InputModality;
use App\OpenRouter\Enums\InstructType;
use App\OpenRouter\Enums\OutputModality;
use App\OpenRouter\Enums\Tokenizer;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ArchitectureData extends Data
{
    public function __construct(
        /** Primary modality of the model */
        public ?string $modality = null,

        /** Supported input modalities
         * @var array<int, InputModality>
         */
        public array $input_modalities = [],

        /** Supported output modalities
         * @var array<int, OutputModality>
         */
        public array $output_modalities = [],

        /** Tokenizer type used by the model */
        public ?Tokenizer $tokenizer = null,

        /** Instruction format type */
        public ?InstructType $instruct_type = null,
    ){}
}
