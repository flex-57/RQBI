<?php

namespace App\Repository;

use App\Entity\Block;
use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Block>
 */
abstract class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param Page $page
     * @return Block[]
     */
    public function findByPageOrdered(Page $page): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.page = :page')
            ->setParameter('page', $page)
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Block $block, bool $flush = false): void
    {
        $this->getEntityManager()->persist($block);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Block $block, bool $flush = false): void
    {
        $this->getEntityManager()->remove($block);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
