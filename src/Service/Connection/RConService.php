<?php

namespace App\Service\Connection;

use RuntimeException;
use App\Entity\Server;
use xPaw\SourceQuery\SourceQuery;
use xPaw\SourceQuery\Exception\SocketException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;

class RConService implements ExecuteInterface, QueryInterface, ConnectionInterface
{
    private Server $server;
    private QueryService $query;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->query = new QueryService($server);
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

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function getPlayerList(): ?array
    {
        try {
            return $this->query->getPlayerList();
        } catch (RuntimeException $e) {
        }

        return explode(
            ',',
            trim(explode(':', $this->execute('list'))[1])
        );
    }

    public function getInfo(): ?array
    {
        return $this->query->getInfo();
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function isPlayerLoggedIn(string $username): bool
    {
        try {
            return $this->query->isPlayerLoggedIn($username);
        } catch (RuntimeException $e) {
        }

        return str_contains($username, $this->execute('list'));
    }
}