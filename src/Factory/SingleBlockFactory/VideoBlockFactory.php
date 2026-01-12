<?php

namespace App\Factory\SingleBlockFactory;

use App\Entity\BlockVideo;
use App\Entity\Enums\BlockTypeEnum;

class VideoBlockFactory implements SingleBlockFactoryInterface
{
    public static function getSupportedType(): BlockTypeEnum
    {
        return BlockTypeEnum::VIDEO;
    }

    public function create(array $data): BlockVideo
    {
        $block = new BlockVideo();
        $block->setUrl($data['url'] ?? '');
        $block->setFormat($data['format'] ?? '');
        $block->setIsAutoplay((bool) ($data['isAutoplay'] ?? false));
        $block->setIsActive((bool) ($data['isActive'] ?? true));

        return $block;
    }
}
