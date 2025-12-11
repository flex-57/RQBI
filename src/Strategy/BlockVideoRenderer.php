<?php

namespace App\Strategy;

use App\Entity\Block;
use App\Entity\BlockVideo;

final class BlockVideoRenderer extends AbstractBlockRenderer
{
    public function supports(Block $block): bool
    {
        return $block instanceof BlockVideo;
    }

    protected function doRender(Block $block): string
    {
        return $this->twig->render('blocks/video.html.twig', [
            'block' => $block,
        ]);
    }
}
