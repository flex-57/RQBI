<?php

namespace App\Twig;

use App\Entity\Page;
use App\Service\BreadcrumbProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadcrumbExtension extends AbstractExtension
{
    public function __construct(
        private BreadcrumbProvider $breadcrumbProvider
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('breadcrumbs', [$this, 'getBreadcrumbs']),
        ];
    }

    /**
     * @return array<array{title: string, url: string, is_current: bool}>
     */
    public function getBreadcrumbs(?Page $page = null): array
    {
        return $this->breadcrumbProvider->generateBreadcrumbData($page);
    }
}
