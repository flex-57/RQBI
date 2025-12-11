<?php

namespace App\Repository;

use App\Entity\BlockImage;
use Doctrine\Persistence\ManagerRegistry;

class BlockImageRepository extends BlockRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockImage::class);
    }
}
