<?php

namespace App\Strategy;

use App\Entity\Block;
use Twig\Environment;

abstract class AbstractBlockRenderer implements BlockRendererInterface
{
    public function __construct(protected Environment $twig) {}

    public function render(Block $block): string
    {
        if (!$this->supports($block)) {
            throw new \InvalidArgumentException(sprintf('Renderer %s does not support %s', static::class, $block::class));
        }

        return $this->doRender($block);
    }

    abstract protected function doRender(Block $block): string;
}
