<?php

namespace App\Factory;

use App\Entity\Block;
use App\Entity\Enums\BlockTypeEnum;

interface BlockFactoryInterface
{
    /**
     * @param array<string,mixed> $data
     */
    public function create(BlockTypeEnum $type, array $data): Block;
}
