<?php

namespace App\Strategy;

use App\Entity\Block;
use App\Entity\BlockSlider;

final class BlockSliderRenderer extends AbstractBlockRenderer
{
    public function supports(Block $block): bool
    {
        return $block instanceof BlockSlider;
    }

    protected function doRender(Block $block): string
    {
        return $this->twig->render('blocks/slider.html.twig', [
            'block' => $block,
        ]);
    }
}
