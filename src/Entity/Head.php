<?php

namespace App\Entity;

use App\Repository\HeadRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeadRepository::class)
 */
class Head
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Meta::class, inversedBy="head")
     */
    private $meta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    public function setMeta(?Meta $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
