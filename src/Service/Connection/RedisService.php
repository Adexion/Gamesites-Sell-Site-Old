<?php

namespace App\Service\Connection;

use App\Entity\Server;

class RedisService implements ExecuteInterface, QueryInterface, ConnectionInterface
{
    private Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    function getConnection()
    {
        // TODO: Implement getConnection() method.
    }

    public function execute($command, string $username = ''): ?string
    {
        // TODO: Implement execute() method.
    }

    public function getPlayerList(): ?array
    {
        // TODO: Implement getPlayerList() method.
    }

    public function getInfo(): ?array
    {
        // TODO: Implement getInfo() method.
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        // TODO: Implement isPlayerLoggedIn() method.
    }
}