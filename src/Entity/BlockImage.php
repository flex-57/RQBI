<?php

namespace App\Entity;

use App\Entity\Enums\BlockTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class BlockImage extends Block
{
    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'L’URL de l’image ne peut pas être vide.')]
    #[Assert\Url(message: 'L’URL de l’image doit être une URL valide.')]
    protected ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le texte alternatif doit avoir au maximum {{ limit }} caractères.'
    )]
    protected ?string $alt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'La légende doit avoir au maximum {{ limit }} caractères.'
    )]
    protected ?string $caption = null;

    public function getType(): BlockTypeEnum
    {
        return BlockTypeEnum::IMAGE;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;
        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): static
    {
        $this->caption = $caption;
        return $this;
    }
}
