<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PositionableTrait
{
    #[ORM\Column(nullable: false, options: ['default' => 1])]
    private int $position = 1;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;
        return $this;
    }
}
