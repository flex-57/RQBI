<?php

namespace App\Listener;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsEntityListener(event: Events::postPersist, entity: Page::class)]
#[AsEntityListener(event: Events::postUpdate, entity: Page::class)]
#[AsEntityListener(event: Events::postRemove, entity: Page::class)]
class MenuCacheInvalidator
{
    public function __construct(private TagAwareCacheInterface $cache){}

    public function __invoke(): void
    {
        $this->cache->invalidateTags(['cache_full_menu']);
    }
}
