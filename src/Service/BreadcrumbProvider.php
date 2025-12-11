<?php

namespace App\Service;

use App\Entity\Page;

class BreadcrumbProvider
{
    /**
     * Génère les données du breadcrumb pour une page
     * Retourne un tableau de ['title' => ..., 'url' => ...]
     * @param Page|null $page
     * @return array<int, array{title: string, url: string, is_current: bool}>
     */
    public function generateBreadcrumbData(?Page $page): array
    {
        if (!$page) {
            return [];
        }

        $breadcrumbs = [];
        $ancestors = $page->getBreadcrumbs();

        foreach ($ancestors as $ancestor) {
            $breadcrumbs[] = [
                'title' => $ancestor->getTitle(),
                'url' => $this->generateUrlForPage($ancestor),
                'is_current' => $ancestor === $page
            ];
        }

        return $breadcrumbs;
    }

    private function generateUrlForPage(Page $page): string
    {
        // Utiliser le slug complet pour les URLs
        return '/' . $page->getFullSlug();
    }
}
