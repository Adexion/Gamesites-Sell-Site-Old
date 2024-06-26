<?php

namespace App\Entity;

use App\Repository\ItemHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemHistoryRepository::class)
 */
class ItemHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", length=2)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $paymentId;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="integer", options={"default": 1})
     */
    private $count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setTotalPrice(?float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }
}
