<?php

namespace App\OpenRouter\Enums;

enum InputModality: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case FILE = 'file';
    case AUDIO = 'audio';
    case VIDEO = 'video';
}
