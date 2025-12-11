<?php

namespace App\DataFixtures;

use App\Entity\{Page, BlockText, BlockImage};
use App\Factory\BlockFactory;
use App\Service\PositionManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private BlockFactory $blockFactory,
        private PositionManager $positionManager,
        private SluggerInterface $slugger
    ) {}

    public function load(ObjectManager $em): void
    {
        // 1. Pages racine
        $home = $this->createPage($em, 'Accueil', isInMainNav: true);
        $commune = $this->createPage($em, 'Ma Commune', isInMainNav: true, parent: $home);
        $ville = $this->createPage($em, 'Ma Ville', isInMainNav: true, parent: $commune);

        // 2. Blocs sur la page "Ma Ville"
        $this->createBlock($em, $ville, 'text', [
            'content' => '# Bienvenue à Behren-lès-Forbach\n\nDécouvrez notre commune.',
            'isActive' => true,
        ]);

        $this->createBlock($em, $ville, 'image', [
            'url' => 'https://picsum.photos/800/600',
            'alt' => 'Photo de la mairie',
        ]);
    }

    private function createPage(ObjectManager $em, string $title, bool $isInMainNav = false, ?Page $parent = null): Page
    {
        $page = new Page();
        $page->setTitle($title)
            ->setSlug($this->slugger->slug($title)->lower()->toString())
            ->setIsPublished(true)
            ->setIsInMainNav($isInMainNav)
            ->setParent($parent);

        $page->setFullSlug($this->generateFullSlug($page));

        $em->persist($page);
        $em->flush();

        $page->setPosition($this->positionManager->getNextPosition(Page::class, ['parent' => $parent]));

        $em->flush();

        return $page;
    }

    private function createBlock(ObjectManager $em, Page $page, string $type, array $data): void
    {
        $block = $this->blockFactory->create(\App\Entity\BlockTypeEnum::from($type), $data);
        $block->setPage($page)
            ->setPosition($this->positionManager->getNextPosition(\App\Entity\Block::class, ['page' => $page]));

        $em->persist($block);
        $em->flush();
    }

    private function generateFullSlug(Page $page): string
    {
        $slugs = [];
        $current = $page;
        while ($current !== null) {
            array_unshift($slugs, $current->getSlug());
            $current = $current->getParent();
        }
        return implode('/', $slugs);
    }
}
