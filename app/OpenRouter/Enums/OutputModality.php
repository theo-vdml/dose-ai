<?php

namespace App\OpenRouter\Enums;

enum OutputModality: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case EMBEDDINGS = 'embeddings';
}
