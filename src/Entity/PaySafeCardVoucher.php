<?php

namespace App\Entity;

use App\Repository\PaySafeCardVoucherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaySafeCardVoucherRepository::class)
 */
class PaySafeCardVoucher
{
    /**
     * @ORM\OneToOne(targetEntity=PaySafeCard::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $paySafeCard;

    /**
     * @ORM\OneToOne(targetEntity=Voucher::class, cascade={"persist", "remove"})
     */
    private $voucher;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    private $hash;

    public function getPaySafeCard(): ?PaySafeCard
    {
        return $this->paySafeCard;
    }

    public function setPaySafeCard(PaySafeCard $paySafeCard): self
    {
        $this->paySafeCard = $paySafeCard;

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
