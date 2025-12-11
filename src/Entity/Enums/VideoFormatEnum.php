<?php

namespace App\Entity\Enums;

enum VideoFormatEnum: string
{
    case MP4 = 'mp4';
    case WEBM = 'webm';
    case OGG = 'ogg';
}
