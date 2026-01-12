<?php

namespace App\Entity;

use App\Entity\Enums\BlockTypeEnum;
use App\Entity\Traits\PositionableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string', length: 32)]
#[ORM\UniqueConstraint(name: 'UNIQ_BLOCK_PAGE_POSITION', columns: ['page_id', 'position'])]
#[ORM\HasLifecycleCallbacks]
abstract class Block
{
    use TimestampableTrait;
    use PositionableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    protected bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'blocks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    protected ?Page $page = null;

    abstract public function getType(): BlockTypeEnum;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): static
    {
        $this->page = $page;

        return $this;
    }
}
