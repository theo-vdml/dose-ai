<?php

namespace App\OpenRouter\Enums;

enum Parameter: string
{
    case TEMPERATURE = 'temperature';
    case TOP_P = 'top_p';
    case TOP_K = 'top_k';
    case TOP_A = 'top_a';
    case MIN_P = 'min_p';
    case FREQUENCY_PENALTY = 'frequency_penalty';
    case PRESENCE_PENALTY = 'presence_penalty';
    case REPETITION_PENALTY = 'repetition_penalty';
    case MAX_TOKENS = 'max_tokens';
    case LOGIT_BIAS = 'logit_bias';
    case LOGPROBS = 'logprobs';
    case TOP_LOGPROBS = 'top_logprobs';
    case SEED = 'seed';
    case RESPONSE_FORMAT = 'response_format';
    case STRUCTURED_OUTPUTS = 'structured_outputs';
    case STOP = 'stop';
    case TOOLS = 'tools';
    case TOOL_CHOICE = 'tool_choice';
    case PARALLEL_TOOL_CALLS = 'parallel_tool_calls';
    case INCLUDE_REASONING = 'include_reasoning';
    case REASONING = 'reasoning';
    case REASONING_EFFORT = 'reasoning_effort';
    case WEB_SEARCH_OPTIONS = 'web_search_options';
    case VERBOSITY = 'verbosity';
}
