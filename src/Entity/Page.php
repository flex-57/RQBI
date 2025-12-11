<?php

namespace App\Entity;

use App\Entity\Traits\PositionableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements \IteratorAggregate<int, Page>
 */
#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_PAGE_PARENT_POSITION', columns: ['parent_id', 'position'])]
#[ORM\UniqueConstraint(name: 'UNIQ_PAGE_SLUG', columns: ['slug'])]
#[ORM\UniqueConstraint(name: 'UNIQ_PAGE_IS_HOMEPAGE', columns: ['is_homepage'])]
#[ORM\Index(name: 'INDEX_PAGE_PARENT_SLUG', columns: ['parent_id', 'slug'])]
#[ORM\HasLifecycleCallbacks]
class Page implements \IteratorAggregate
{
    use TimestampableTrait, PositionableTrait;

    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 180, nullable: false)]
    #[Assert\NotBlank(message: 'Le titre de la page ne peut pas être vide.')]
    #[Assert\Length(
        min: 2,
        max: 180,
        minMessage: 'Le titre de la page doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le titre de la page doit contenir au plus {{ limit }} caractères.'
    )]
    private ?string $title = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Le slug de la page ne peut pas être vide.')]
    #[Assert\Regex(pattern: '/^[a-z0-9-]+$/', message: 'Le slug ne peut contenir que des lettres minuscules, chiffres et tirets')]
    private ?string $slug = null;

    #[ORM\Column(length: 220, unique: true)]
    #[Assert\NotBlank(message: 'Le slug complet de la page ne peut pas être vide.')]
    private string $fullSlug;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isHomepage = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isPublished = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isInMainNav = false;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $children;

    /**
     * @var Collection<int, Block>
     */
    #[ORM\OneToMany(targetEntity: Block::class, mappedBy: 'page', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $blocks;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->blocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getFullSlug(): ?string
    {
        return $this->fullSlug;
    }

    public function setFullSlug(string $fullSlug): static
    {
        $this->fullSlug = $fullSlug;
        return $this;
    }

    public function isHomepage(): bool
    {
        return $this->isHomepage;
    }

    public function setIsHomepage(bool $isHomepage): static
    {
        $this->isHomepage = $isHomepage;
        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;
        return $this;
    }

    public function isInMainNav(): bool
    {
        return $this->isInMainNav;
    }

    public function setIsInMainNav(bool $isInMainNav): static
    {
        $this->isInMainNav = $isInMainNav;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Block>
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): static
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks->add($block);
            $block->setPage($this);
        }
        return $this;
    }

    public function removeBlock(Block $block): static
    {
        if ($this->blocks->removeElement($block)) {
            if ($block->getPage() === $this) {
                $block->setPage(null);
            }
        }
        return $this;
    }

    /**
     * @return array<self>
     */
    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $current = $this;
        while ($current !== null) {
            $breadcrumbs[] = $current;
            $current = $current->getParent();
        }
        return array_reverse($breadcrumbs);
    }

    public function getIterator(): \Traversable
    {
        return $this->children->getIterator();
    }
}
