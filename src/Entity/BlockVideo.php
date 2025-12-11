<?php

namespace App\Entity;

use App\Entity\Enums\BlockTypeEnum;
use App\Entity\Enums\VideoFormatEnum;
use App\Repository\BlockVideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlockVideoRepository::class)]
class BlockVideo extends Block
{
    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'L’URL de la vidéo ne peut pas être vide.')]
    #[Assert\Url(message: 'L’URL de la vidéo doit être une URL valide.')]
    protected ?string $url = null;

    #[ORM\Column(length: 20, nullable: false, enumType: VideoFormatEnum::class)]
    #[Assert\NotNull(message: 'Le format de la vidéo doit être spécifié.')]
    protected ?VideoFormatEnum $format = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    protected bool $isAutoplay = false;

    public function getType(): BlockTypeEnum
    {
        return BlockTypeEnum::VIDEO;
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

    public function getFormat(): ?VideoFormatEnum
    {
        return $this->format;
    }

    public function setFormat(VideoFormatEnum|string $format): static
    {
        $this->format = $format instanceof VideoFormatEnum ? $format : VideoFormatEnum::from($format);
        return $this;
    }

    public function isAutoplay(): bool
    {
        return $this->isAutoplay;
    }

    public function setIsAutoplay(bool $isAutoplay): static
    {
        $this->isAutoplay = $isAutoplay;
        return $this;
    }
}
