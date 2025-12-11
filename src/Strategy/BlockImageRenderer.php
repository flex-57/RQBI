<?php

namespace App\Strategy;

use App\Entity\Block;
use App\Entity\BlockImage;

final class BlockImageRenderer extends AbstractBlockRenderer
{
    public function supports(Block $block): bool
    {
        return $block instanceof BlockImage;
    }

    protected function doRender(Block $block): string
    {
        return $this->twig->render('blocks/image.html.twig', [
            'block' => $block,
        ]);
    }
}
