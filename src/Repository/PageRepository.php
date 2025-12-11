<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Page::class);
    }

    public function createBasicHomepageIfNotExists(): Page
    {
        $homepage = (new Page())
            ->setTitle('Accueil')
            ->setSlug('accueil')
            ->setFullSlug('accueil')
            ->setIsHomepage(true)
            ->setIsPublished(true)
            ->setIsInMainNav(true);
        $this->save($homepage, true);

        return $homepage;
    }

    public function ensureSingleHomepage(?Page $excludePage = null): void
    {
        $qb = $this->createQueryBuilder('p')
            ->update()
            ->set('p.isHomepage', ':false')
            ->where('p.isHomepage = :true')
            ->setParameter('false', false)
            ->setParameter('true', true);

        if ($excludePage !== null) {
            $qb->andWhere('p != :exclude')
               ->setParameter('exclude', $excludePage);
        }

        $qb->getQuery()->execute();
    }

    public function findOneByFullSlugWithBlocks(string $fullSlug): ?Page
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.blocks', 'b')->addSelect('b')
            ->where('p.fullSlug = :fullSlug')
            ->setParameter('fullSlug', $fullSlug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Page[]
     */
    public function findRootPagesWithChildren(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.children', 'children')->addSelect('children')
            ->leftJoin('children.children', 'grandchildren')->addSelect('grandchildren')
            ->where('p.parent IS NULL')
            ->andWhere('p.isInMainNav = :isInMainNav')
            ->setParameter('isInMainNav', true)
            ->orderBy('p.position', 'ASC')
            ->addOrderBy('children.position', 'ASC')
            ->addOrderBy('grandchildren.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string[]
     */
    public function findSlugsStartingWith(string $prefix): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.slug')
            ->where('p.slug LIKE :prefix')
            ->setParameter('prefix', $prefix . '%')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @return Page[]
     */
    public function findPublishedOrdered(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Page $page, bool $flush = false): void
    {
        $this->getEntityManager()->persist($page);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Page $page, bool $flush = false): void
    {
        $this->getEntityManager()->remove($page);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
