<?php

namespace App\Service\Connection;

use App\Entity\Server;

class QueryService implements QueryInterface, ConnectionInterface
{
    private ?Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function getInfo(): ?array
    {
        return $this->getConnection()->getInfo() ?: [];
    }

    public function getConnection(): QueryInstance
    {
        $queryMinecraft = new QueryInstance();
        $queryMinecraft->connect($this->server->getMinecraftQueryIp(), $this->server->getMinecraftQueryPort());

        return $queryMinecraft;
    }

    public function getPlayerList(): ?array
    {
        return $this->getConnection()->getPlayers() ?: [];
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        return in_array($username, $this->getPlayerList());
    }
}