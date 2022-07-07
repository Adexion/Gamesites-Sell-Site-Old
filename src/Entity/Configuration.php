<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationRepository::class)
 */
class Configuration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $template;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $simplePaySafeCard;

    /**
     * @ORM\Column(type="text", options={"default": true})
     */
    private $simplePayPal;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $target;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $showBigLogo = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        if (!$logo) {
            return $this;
        }

        $this->logo = $logo;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getSimplePaySafeCard(): ?bool
    {
        return $this->simplePaySafeCard;
    }

    public function setSimplePaySafeCard(?bool $simplePaySafeCard): self
    {
        $this->simplePaySafeCard = $simplePaySafeCard;

        return $this;
    }

    public function getSimplePayPal(): ?string
    {
        return $this->simplePayPal;
    }

    public function setSimplePayPal(?string $simplePayPal): self
    {
        $this->simplePayPal = $simplePayPal;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function setTarget(?float $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getTarget(): ?float
    {
        return $this->target;
    }

    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function getShowBigLogo(): ?bool
    {
        return $this->showBigLogo;
    }

    public function setShowBigLogo(bool $showBigLogo): self
    {
        $this->showBigLogo = $showBigLogo;

        return $this;
    }
}
