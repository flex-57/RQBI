<?php

namespace App\Twig;

use App\Service\MenuProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(private MenuProvider $menuProvider) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('full_menu', [$this, 'getFullMenu']),
        ];
    }

    /**
     * @return array<int, array{
     *      title: string, slug: string, fullSlug: string, menuGroups: array<int, array{
     *          title: string, slug: string, fullSlug: string, menuItems: array<int, array{
     *              title: string, slug: string, fullSlug: string
     *          }>
     *      }>
     *  }>
     */
    public function getFullMenu(): array
    {
        return $this->menuProvider->getFullMenu();
    }
}
