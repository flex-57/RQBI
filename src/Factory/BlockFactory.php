<?php

namespace App\Factory;

use App\Entity\Block;
use App\Entity\Enums\BlockTypeEnum;
use App\Factory\SingleBlockFactory\SingleBlockFactoryInterface;

class BlockFactory implements BlockFactoryInterface
{
    /** @var array<BlockTypeEnum, SingleBlockFactoryInterface> */
    private array $factories = [];

    /** @param iterable<SingleBlockFactoryInterface> $factories */
    public function __construct(iterable $factories)
    {
        foreach ($factories as $factory) {
            $this->factories[$factory::getSupportedType()->value] = $factory;
        }
    }

    public function create(BlockTypeEnum $type, array $data): Block
    {
        if (!isset($this->factories[$type->value])) {
            throw new \InvalidArgumentException('No factory registered for block type '.$type->value);
        }

        return $this->factories[$type->value]->create($data);
    }
}
