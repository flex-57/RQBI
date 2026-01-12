<?php

namespace App\Factory\SingleBlockFactory;

use App\Entity\BlockImage;
use App\Entity\Enums\BlockTypeEnum;

final class ImageBlockFactory implements SingleBlockFactoryInterface
{
    public static function getSupportedType(): BlockTypeEnum
    {
        return BlockTypeEnum::IMAGE;
    }

    public function create(array $data): BlockImage
    {
        $block = new BlockImage();
        $block->setUrl($data['url'] ?? '');
        $block->setAlt($data['alt'] ?? null);
        $block->setCaption($data['caption'] ?? null);
        $block->setIsActive((bool) ($data['isActive'] ?? true));

        return $block;
    }
}
