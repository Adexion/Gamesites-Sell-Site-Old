<?php

namespace App\Entity;

use App\Repository\MetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetaRepository::class)
 */
class Meta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity=Head::class, mappedBy="meta")
     */
    private $head;

    public function __construct()
    {
        $this->head = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, Head>
     */
    public function getHead(): Collection
    {
        return $this->head;
    }

    public function addHead(Head $head): self
    {
        if (!$this->head->contains($head)) {
            $this->head[] = $head;
            $head->setMeta($this);
        }

        return $this;
    }

    public function removeHead(Head $head): self
    {
        if ($this->head->removeElement($head)) {
            // set the owning side to null (unless already changed)
            if ($head->getMeta() === $this) {
                $head->setMeta(null);
            }
        }

        return $this;
    }
}
