<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TwitchRepository;
/**
 * @ORM\Entity(repositoryClass=TwitchRepository::class)
 */
class TwitchStreamer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $channelName;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $minecraftName;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     */
    private $server;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChannelName(): ?string
    {
        return $this->channelName;
    }

    public function setChannelName(string $channelName): self
    {
        $this->channelName = $channelName;

        return $this;
    }

    public function getMinecraftName(): ?string
    {
        return $this->minecraftName;
    }

    public function setMinecraftName(string $minecraftName): self
    {
        $this->minecraftName = $minecraftName;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }
}
