<?php

namespace App\Factory\SingleBlockFactory;

use App\Entity\BlockText;
use App\Entity\Enums\BlockTypeEnum;

final class TextBlockFactory implements SingleBlockFactoryInterface
{
    public static function getSupportedType(): BlockTypeEnum
    {
        return BlockTypeEnum::TEXT;
    }

    public function create(array $data): BlockText
    {
        $block = new BlockText();
        $block->setContent($data['content'] ?? '');
        $block->setIsActive((bool)($data['isActive'] ?? true));
        return $block;
    }
}
