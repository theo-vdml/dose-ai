<?php

namespace App\OpenRouter\Enums;

enum InstructType: string
{
    case NONE = 'none';
    case AIROBOROS = 'airoboros';
    case ALPACA = 'alpaca';
    case ALPACA_MODIF = 'alpaca-modif';
    case CHATML = 'chatml';
    case CLAUDE = 'claude';
    case CODE_LLAMA = 'code-llama';
    case GEMMA = 'gemma';
    case LLAMA2 = 'llama2';
    case LLAMA3 = 'llama3';
    case MISTRAL = 'mistral';
    case NEMOTRON = 'nemotron';
    case NEURAL = 'neural';
    case OPENCHAT = 'openchat';
    case PHI3 = 'phi3';
    case RWKV = 'rwkv';
    case VICUNA = 'vicuna';
    case ZEPHYR = 'zephyr';
    case DEEPSEEK_R1 = 'deepseek-r1';
    case DEEPSEEK_V3_1 = 'deepseek-v3.1';
    case QWQ = 'qwq';
    case QWEN3 = 'qwen3';
}
