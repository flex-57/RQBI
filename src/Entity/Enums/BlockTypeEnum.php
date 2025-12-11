<?php

namespace App\Entity\Enums;

enum BlockTypeEnum: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case VIDEO = 'video';
    case SLIDER = 'slider';
}
