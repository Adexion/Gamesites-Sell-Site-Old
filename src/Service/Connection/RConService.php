<?php

namespace App\Service\Connection;

use App\Entity\Server;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;
use xPaw\SourceQuery\SourceQuery;

class RConService implements ExecuteInterface, QueryInterface, ConnectionInterface
{
    private Server $server;
    private QueryService $queryService;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->queryService = new QueryService($server);
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function execute($command, string $username = '', int $amount = 1): ?string
    {
        return $this->getConnection()->rcon(str_replace(['%player%', '%amount%'], [$username,$amount], $command));
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException */
    public function getConnection(): SourceQuery
    {
        $sourceQuery = new SourceQuery();

        error_reporting(E_ALL ^ E_WARNING);
        try {
            $sourceQuery->connect($this->server->getConIp() ?? '', $this->server->getConPort() ?? 0, 1);
            $sourceQuery->setRConPassword($this->server->getConPassword());
        } catch (SocketException $e) {
        }

        return $sourceQuery;
    }

    public function getPlayerList(): ?array
    {
        return $this->queryService->getPlayerList();
    }

    public function getInfo(): ?array
    {
        return $this->queryService->getInfo();
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        return $this->queryService->isPlayerLoggedIn($username);
    }
}