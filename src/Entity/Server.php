<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 */
class Server
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $connectionType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conIp;

    /**
     * @ORM\Column(type="smallint", length=255)
     */
    private $conPort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conPassword;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $serverName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isDefault;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minecraftQueryIp;

    /**
     * @ORM\Column(type="smallint", length=255, nullable=true)
     */
    private $minecraftQueryPort;


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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConIp(): ?string
    {
        return $this->conIp;
    }

    public function setConIp(?string $conIp): self
    {
        $this->conIp = $conIp;

        return $this;
    }

    public function getConPort(): ?int
    {
        return $this->conPort;
    }

    public function setConPort(?int $conPort): self
    {
        $this->conPort = $conPort;

        return $this;
    }

    public function getConPassword(): ?string
    {
        return $this->conPassword;
    }

    public function setConPassword(?string $conPassword): self
    {
        $this->conPassword = $conPassword;

        return $this;
    }

    public function getServerName(): ?string
    {
        return $this->serverName;
    }

    public function setServerName(string $serverName): self
    {
        $this->serverName = $serverName;

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(?bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setConnectionType(?string $connectionType): self
    {
        $this->connectionType = $connectionType;

        return $this;
    }

    public function getConnectionType(): ?string
    {
        return $this->connectionType;
    }
}
