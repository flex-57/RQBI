<?php

namespace App\Entity;

use App\Entity\Enums\BlockTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class BlockText extends Block
{
    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: 'Le contenu texte ne peut pas être vide.')]
    #[Assert\Length(
        min: 10,
        max: 5000,
        minMessage: 'Le contenu texte doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le contenu texte ne peut pas dépasser {{ limit }} caractères.'
    )]
    protected ?string $content = null;

    public function getType(): BlockTypeEnum
    {
        return BlockTypeEnum::TEXT;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }
}
