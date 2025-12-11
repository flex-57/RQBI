<?php

namespace App\Service;

use App\Repository\PageRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugService
{
    public function __construct(
        private PageRepository $pageRepository,
        private SluggerInterface $slugger
    ) {}

    public function generateUniqueSlug(string $title): string
    {
        $baseSlug = $this->slugger->slug($title)->lower()->toString();

        $existingSlugs = $this->pageRepository->findSlugsStartingWith($baseSlug);

        return $this->ensureUniqueSlug($baseSlug, $existingSlugs);
    }

    /**
     * @param string[] $existingSlugs
     */
    public function ensureUniqueSlug(string $baseSlug, array $existingSlugs): string
    {
        if (!in_array($baseSlug, $existingSlugs, true)) {
            return $baseSlug;
        }

        $counter = 1;
        do {
            $slug = $baseSlug . '-' . $counter++;
        } while (in_array($slug, $existingSlugs, true));

        return $slug;
    }
}
