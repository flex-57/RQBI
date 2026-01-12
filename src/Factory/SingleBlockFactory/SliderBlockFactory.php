<?php

namespace App\Factory\SingleBlockFactory;

use App\Entity\BlockSlider;
use App\Entity\Enums\BlockTypeEnum;

final class SliderBlockFactory implements SingleBlockFactoryInterface
{
    public static function getSupportedType(): BlockTypeEnum
    {
        return BlockTypeEnum::SLIDER;
    }

    public function create(array $data): BlockSlider
    {
        $block = new BlockSlider();
        $block->setImages($data['images'] ?? []);
        $block->setIsActive((bool) ($data['isActive'] ?? true));

        return $block;
    }
}
