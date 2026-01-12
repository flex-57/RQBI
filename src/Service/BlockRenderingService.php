<?php

namespace App\Service;

use App\Entity\Block;
use App\Strategy\BlockRendererInterface;

class BlockRenderingService
{
    /** @var BlockRendererInterface[] */
    private array $renderers = [];

    /**
     * @param iterable<BlockRendererInterface> $renderers
     */
    public function __construct(iterable $renderers)
    {
        foreach ($renderers as $renderer) {
            $this->renderers[] = $renderer;
        }
    }

    public function render(Block $block): string
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($block)) {
                return $renderer->render($block);
            }
        }

        return '<!-- No renderer for '.$block::class.' -->';
    }

    /**
     * @param iterable<Block> $blocks
     */
    public function renderAll(iterable $blocks): string
    {
        $html = '';
        foreach ($blocks as $block) {
            if ($block->isActive()) {
                $html .= $this->render($block);
            }
        }

        return $html;
    }
}
