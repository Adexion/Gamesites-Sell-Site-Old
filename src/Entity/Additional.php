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
     * @ORM\Column(type="text")
     */
    private $descriptionOne;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionTwo;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionThree;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionFour;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $discord;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ts3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $yt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tiktok;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $trailer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionOne(): ?string
    {
        return $this->descriptionOne;
    }

    public function setDescriptionOne(string $descriptionOne): self
    {
        $this->descriptionOne = $descriptionOne;

        return $this;
    }

    public function getDescriptionTwo(): ?string
    {
        return $this->descriptionTwo;
    }

    public function setDescriptionTwo(string $descriptionTwo): self
    {
        $this->descriptionTwo = $descriptionTwo;

        return $this;
    }

    public function getDescriptionThree(): ?string
    {
        return $this->descriptionThree;
    }

    public function setDescriptionThree(string $descriptionThree): self
    {
        $this->descriptionThree = $descriptionThree;

        return $this;
    }

    public function getDescriptionFour(): ?string
    {
        return $this->descriptionFour;
    }

    public function setDescriptionFour(string $descriptionFour): self
    {
        $this->descriptionFour = $descriptionFour;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(string $discord): self
    {
        $this->discord = $discord;

        return $this;
    }

    public function getTs3(): ?string
    {
        return $this->ts3;
    }

    public function setTs3(string $ts3): self
    {
        $this->ts3 = $ts3;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getYt(): ?string
    {
        return $this->yt;
    }

    public function setYt(string $yt): self
    {
        $this->yt = $yt;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getTiktok(): ?string
    {
        return $this->tiktok;
    }

    public function setTiktok(string $tiktok): self
    {
        $this->tiktok = $tiktok;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'descriptionOne' => $this->getDescriptionOne(),
            'descriptionTwo' => $this->getDescriptionTwo(),
            'descriptionThree' => $this->getDescriptionThree(),
            'descriptionFour' => $this->getDescriptionFour(),
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
}
