<?php

namespace App\Twig;

use App\Entity\Block;
use App\Service\BlockRenderingService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlockExtension extends AbstractExtension
{
    public function __construct(
        private BlockRenderingService $blockRenderingService
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_block', [$this, 'renderBlock'], ['is_safe' => ['html']]),
            new TwigFunction('render_blocks', [$this, 'renderBlocks'], ['is_safe' => ['html']]),
        ];
    }

    public function renderBlock(Block $block): string
    {
        return $this->blockRenderingService->render($block);
    }

    /**
     * @param iterable<Block> $blocks
     */
    public function renderBlocks(iterable $blocks): string
    {
        return $this->blockRenderingService->renderAll($blocks);
    }
}
