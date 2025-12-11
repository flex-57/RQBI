<?php

namespace App\Factory\SingleBlockFactory;

use App\Entity\Block;
use App\Entity\Enums\BlockTypeEnum;

interface SingleBlockFactoryInterface
{
    public static function getSupportedType(): BlockTypeEnum;

    /** @param array<string,mixed> $data */
    public function create(array $data): Block;
}
