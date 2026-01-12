<?php

namespace App\Strategy;

use App\Entity\Block;
use Twig\Environment;

final class BlockDefautRenderer implements BlockRendererInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function supports(Block $block): bool
    {
        return true;
    }

    public function render(Block $block): string
    {
        $template = 'blocks/'.$block->getType()->value.'.html.twig';

        if (!$this->twig->getLoader()->exists($template)) {
            return '<!-- No template for block type '.$block->getType()->value.' -->';
        }

        return $this->twig->render($template, ['block' => $block]);
    }
}
