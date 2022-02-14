<?php

namespace App\Service\Connection;

use App\Entity\Server;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

class QueryService implements QueryInterface, ConnectionInterface
{
    private ?Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function getInfo(): ?array
    {
        try {
            return $this->getConnection()->GetInfo() ?: [];
        } catch (MinecraftQueryException $e) {
            return [];
        }
    }

    /** @throws MinecraftQueryException */
    public function getConnection(): MinecraftQuery
    {
        $queryMinecraft = new MinecraftQuery();
        $queryMinecraft->connect($this->server->getMinecraftQueryIp(), $this->server->getMinecraftQueryPort());

        return $queryMinecraft;
    }

    public function getPlayerList(): ?array
    {
        try {
            return $this->getConnection()->GetPlayers() ?: [];
        } catch (MinecraftQueryException $e) {
            return [];
        }
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        return in_array($username, $this->getPlayerList());
    }
}