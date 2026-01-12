<?php

namespace App\Entity;

use App\Entity\Enums\BlockTypeEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BlockSlider extends Block
{
    /** @var array<int, string> */
    #[ORM\Column(type: 'json')]
    private array $images = [];

    public function getType(): BlockTypeEnum
    {
        return BlockTypeEnum::SLIDER;
    }

    /** @return array<int, string> */
    public function getImages(): array
    {
        return $this->images;
    }

    /** @param array<int, string> $images */
    public function setImages(array $images): static
    {
        $this->images = $images;

        return $this;
    }
}
