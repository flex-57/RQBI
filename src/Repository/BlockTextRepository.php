<?php

namespace App\Repository;

use App\Entity\BlockText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BlockTextRepository extends BlockRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockText::class);
    }
}
