<?php

namespace App\Repository;

use App\Entity\BlockVideo;
use Doctrine\Persistence\ManagerRegistry;

class BlockVideoRepository extends BlockRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockVideo::class);
    }
}
