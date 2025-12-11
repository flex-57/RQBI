<?php

namespace App\Service;

use App\Repository\PageRepository;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MenuProvider
{
    public function __construct(
        private PageRepository $pageRepository,
        private TagAwareCacheInterface $cache
    ) {}

    /**
     * Retourne le menu structuré avec les fullSlugs
     * [
     *   [
     *     'title' => 'Ma commune',
     *     'slug' => 'ma-commune',
     *     'fullSlug' => 'ma-commune',
     *     'menuGroups' => [
     *       [
     *         'title' => 'Ma ville Behren-lès-Forbach',
     *         'slug' => 'ma-ville-behren-les-forbach',
     *         'fullSlug' => 'ma-commune/ma-ville-behren-les-forbach',
     *         'menuItems' => [
     *           [
     *             'title' => 'Behren-lès-Forbach',
     *             'slug' => 'behren-les-forbach',
     *             'fullSlug' => 'ma-commune/ma-ville-behren-les-forbach/behren-les-forbach'
     *           ]
     *         ]
     *       ]
     *     ]
     *   ]
     * ]
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
        return $this->cache->get('full_menu', function(ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag(['cache_full_menu']);

            $rootPages = $this->pageRepository->findRootPagesWithChildren();
            $menu = [];

            // Niveau 1 (root)
            foreach ($rootPages as $rootPage) {
                $section = [
                    'title' => $rootPage->getTitle(),
                    'slug' => $rootPage->getSlug(),
                    'fullSlug' => $rootPage->getFullSlug(),
                    'menuGroups' => []
                ];

                // Niveau 2
                foreach ($rootPage->getChildren() as $groupPage) {
                    $group = [
                        'title' => $groupPage->getTitle(),
                        'slug' => $groupPage->getSlug(),
                        'fullSlug' => $groupPage->getFullSlug(),
                        'menuItems' => []
                    ];

                    // Niveau 3
                    foreach ($groupPage->getChildren() as $itemPage) {
                        $group['menuItems'][] = [
                            'title' => $itemPage->getTitle(),
                            'slug' => $itemPage->getSlug(),
                            'fullSlug' => $itemPage->getFullSlug()
                        ];
                    }

                    $section['menuGroups'][] = $group;
                }

                $menu[] = $section;
            }

            return $menu;
        });
    }
}
