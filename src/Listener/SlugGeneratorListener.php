<?php

namespace App\Listener;

use App\Entity\Page;
use App\Service\SlugService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, entity: Page::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Page::class)]
class SlugGeneratorListener
{
    public function __construct(
        private SlugService $slugService,
        private EntityManagerInterface $entityManager
    ) {}

    public function prePersist(Page $page): void
    {
        $this->generateSlug($page);
        $this->generateFullSlug($page);
    }

    public function preUpdate(Page $page): void
    {
        $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($page); // Récupérer les changements de l'entité
        // Générer le slug uniquement si le slug ou le parent a changé
        if(isset($changeSet['slug']) || isset($changeSet['parent'])) {
            // $this->generateSlug($page);
            $this->generateFullSlug($page);
        }
    }

    private function generateSlug(Page $page): void
    {
        // Ne génère le slug que s'il n'existe pas ou s'il est vide ou pour forcer la (ré)génération
        if (!$page->getSlug() && $page->getTitle()) {
            $slug = $this->slugService->generateUniqueSlug($page->getTitle());
            $page->setSlug($slug);
        }
    }

    private function generateFullSlug(Page $page): void
    {
        $slugs = [];
        $current = $page;
        while ($current !== null) {
            array_unshift($slugs, $current->getSlug());
            $current = $current->getParent();
        }
        $page->setFullSlug(implode('/', $slugs));
    }
}
