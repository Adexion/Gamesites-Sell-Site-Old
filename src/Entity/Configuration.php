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
     * @ORM\Column(type="string", length=255)
     */
    private $serverName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minecraftQueryIp;

    /**
     * @ORM\Column(type="smallint", length=255, nullable=true)
     */
    private $minecraftQueryPort;

    /**
     * @ORM\Column(type="string", length=16)
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(?string $serverName): self
    {
        $this->serverName = $serverName;

        return $this;
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

    public function getMinecraftQueryIp(): ?string
    {
        return $this->minecraftQueryIp;
    }

    public function setMinecraftQueryIp(?string $minecraftQueryIp): self
    {
        $this->minecraftQueryIp = $minecraftQueryIp;

        return $this;
    }

    public function getMinecraftQueryPort(): ?int
    {
        return $this->minecraftQueryPort;
    }

    public function setMinecraftQueryPort(?int $minecraftQueryPort): self
    {
        $this->minecraftQueryPort = $minecraftQueryPort;

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

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }
}
