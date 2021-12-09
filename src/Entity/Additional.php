<?php

namespace App\Entity;

use App\Repository\AdditionalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdditionalRepository::class)
 */
class Additional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $siteTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $mainText;

    /**
     * @ORM\Column(type="text")
     */
    private $mainDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $trailerText;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $guildText;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $discord;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $ts3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tiktok;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trailer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainText(): ?string
    {
        return $this->mainText;
    }

    public function setMainText(string $mainText): self
    {
        $this->mainText = $mainText;

        return $this;
    }

    public function getMainDescription(): ?string
    {
        return $this->mainDescription;
    }

    public function setMainDescription(string $mainDescription): self
    {
        $this->mainDescription = $mainDescription;

        return $this;
    }

    public function getTrailerText(): ?string
    {
        return $this->trailerText;
    }

    public function setTrailerText(?string $trailerText): self
    {
        $this->trailerText = $trailerText;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getTs3(): ?string
    {
        return $this->ts3;
    }

    public function setTs3(?string $ts3): self
    {
        $this->ts3 = $ts3;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getYt(): ?string
    {
        return $this->yt;
    }

    public function setYt(?string $yt): self
    {
        $this->yt = $yt;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getTiktok(): ?string
    {
        return $this->tiktok;
    }

    public function setTiktok(?string $tiktok): self
    {
        $this->tiktok = $tiktok;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'siteTitle' => $this->getSiteTitle(),
            'mainText' => $this->getMainText(),
            'mainDescription' => $this->getMainDescription(),
            'trailerText' => $this->getTrailerText(),
            'guildText' => $this->getGuildText(),
            'trailer' => $this->getTrailer(),
            'discord' => $this->getDiscord(),
            'ts3' => $this->getTs3(),
            'facebook' => $this->getFacebook(),
            'yt' => $this->getYt(),
            'instagram' => $this->getInstagram(),
            'tiktok' => $this->getTiktok(),
        ];
    }

    public function getTrailer(): ?string
    {
        return $this->trailer;
    }

    public function setTrailer(?string $trailer): self
    {
        $this->trailer = $trailer;

        return $this;
    }

    public function getSiteTitle(): ?string
    {
        return $this->siteTitle;
    }

    public function setSiteTitle(?string $siteTitle): self
    {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    public function getGuildText(): ?string
    {
        return $this->guildText;
    }

    public function setGuildText(?string $guildText): self
    {
        $this->guildText = $guildText;

        return $this;
    }
}
