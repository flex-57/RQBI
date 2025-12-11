<?php

namespace App\Strategy;

use App\Entity\Block;

interface BlockRendererInterface
{
    public function render(Block $block): string;

    public function supports(Block $block): bool;
}
