<?php

namespace App\Strategy;

use App\Entity\Block;
use App\Entity\BlockText;

final class BlockTextRenderer extends AbstractBlockRenderer
{
    public function supports(Block $block): bool
    {
        return $block instanceof BlockText;
    }

    protected function doRender(Block $block): string
    {
        return $this->twig->render('blocks/text.html.twig', [
            'block' => $block,
        ]);
    }
}
