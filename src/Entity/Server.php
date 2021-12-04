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

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $serverName;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isDefault;

    public function getId(): ?int
    {
        return $this->id;
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
}
