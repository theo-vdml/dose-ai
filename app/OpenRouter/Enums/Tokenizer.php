<?php

namespace App\OpenRouter\Enums;

enum Tokenizer: string
{
    case ROUTER = 'Router';
    case MEDIA = 'Media';
    case OTHER = 'Other';
    case GPT = 'GPT';
    case CLAUDE = 'Claude';
    case GEMINI = 'Gemini';
    case GROK = 'Grok';
    case COHERE = 'Cohere';
    case NOVA = 'Nova';
    case QWEN = 'Qwen';
    case QWEN3 = 'Qwen3';
    case YI = 'Yi';
    case DEEPSEEK = 'DeepSeek';
    case MISTRAL = 'Mistral';
    case LLAMA2 = 'Llama2';
    case LLAMA3 = 'Llama3';
    case LLAMA4 = 'Llama4';
    case PALM = 'PaLM';
    case RWKV = 'RWKV';
}
