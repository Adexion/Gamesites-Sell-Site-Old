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
     * @ORM\Column(type="string", length=255)
     */
    private $RConIp;

    /**
     * @ORM\Column(type="smallint", length=255)
     */
    private $RConPort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RConPassword;

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

    public function getRConIp(): ?string
    {
        return $this->RConIp;
    }

    public function setRConIp(?string $RConIp): self
    {
        $this->RConIp = $RConIp;

        return $this;
    }

    public function getRConPort(): ?int
    {
        return $this->RConPort;
    }

    public function setRConPort(?int $RConPort): self
    {
        $this->RConPort = $RConPort;

        return $this;
    }

    public function getRConPassword(): ?string
    {
        return $this->RConPassword;
    }

    public function setRConPassword(?string $RConPassword): self
    {
        $this->RConPassword = $RConPassword;

        return $this;
    }
}
