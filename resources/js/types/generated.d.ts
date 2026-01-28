export type ArchitectureData = {
modality: string | null;
input_modalities: { [key: number]: InputModality };
output_modalities: { [key: number]: OutputModality };
tokenizer: Tokenizer | null;
instruct_type: InstructType | null;
};
export type DefaultParametersData = {
temperature: number | null;
top_p: number | null;
frequency_penalty: number | null;
};
export type InputModality = 'text' | 'image' | 'file' | 'audio' | 'video';
export type InstructType = 'none' | 'airoboros' | 'alpaca' | 'alpaca-modif' | 'chatml' | 'claude' | 'code-llama' | 'gemma' | 'llama2' | 'llama3' | 'mistral' | 'nemotron' | 'neural' | 'openchat' | 'phi3' | 'rwkv' | 'vicuna' | 'zephyr' | 'deepseek-r1' | 'deepseek-v3.1' | 'qwq' | 'qwen3';
export type ModelData = {
id: string;
canonical_slug: string;
name: string;
created: number;
pricing: PricingData;
context_length: number | null;
architecture: ArchitectureData;
top_provider: TopProviderData;
per_request_limits: PerRequestLimitsData | null;
supported_parameters: { [key: number]: Parameter };
default_parameters: DefaultParametersData | null;
hugging_face_id: string | null;
description: string | null;
};
export type OutputModality = 'text' | 'image' | 'embeddings';
export type Parameter = 'temperature' | 'top_p' | 'top_k' | 'top_a' | 'min_p' | 'frequency_penalty' | 'presence_penalty' | 'repetition_penalty' | 'max_tokens' | 'logit_bias' | 'logprobs' | 'top_logprobs' | 'seed' | 'response_format' | 'structured_outputs' | 'stop' | 'tools' | 'tool_choice' | 'parallel_tool_calls' | 'include_reasoning' | 'reasoning' | 'reasoning_effort' | 'web_search_options' | 'verbosity';
export type PerRequestLimitsData = {
prompt_tokens: number;
completion_tokens: number;
};
export type PricingData = {
prompt: string;
completion: string;
request: string | null;
image: string | null;
image_token: string | null;
image_output: string | null;
audio: string | null;
input_audio_cache: string | null;
web_search: string | null;
internal_reasoning: string | null;
input_cache_read: string | null;
input_cache_write: string | null;
discount: number | null;
};
export type Tokenizer = 'Router' | 'Media' | 'Other' | 'GPT' | 'Claude' | 'Gemini' | 'Grok' | 'Cohere' | 'Nova' | 'Qwen' | 'Qwen3' | 'Yi' | 'DeepSeek' | 'Mistral' | 'Llama2' | 'Llama3' | 'Llama4' | 'PaLM' | 'RWKV';
export type TopProviderData = {
is_moderated: boolean;
context_length: number | null;
max_completion_tokens: number | null;
};
