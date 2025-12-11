<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\Block;
use App\Entity\Page;

class PositionManager
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * @param class-string $entityClass
     * @param array<string, mixed> $criteria
     */
    public function getNextPosition(string $entityClass, array $criteria = []): int
    {
        $qb = $this->em->createQueryBuilder()
            ->select('COALESCE(MAX(e.position), 0)')
            ->from($entityClass, 'e');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("e.$field = :$field")
                ->setParameter($field, $value);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() + 1;
    }

    /**
     * @param EntityRepository<object> $repository
     * @param array<string, mixed> $criteria
     */
    public function reorderPositions(EntityRepository $repository, array $criteria = []): void
    {
        $entities = $repository->findBy($criteria, ['position' => 'ASC']);

        foreach ($entities as $index => $entity) {
            /** @var Block|Page $entity */
            $entity->setPosition($index + 1);
        }

        $this->em->flush();
    }
}
