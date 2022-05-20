<?php

namespace App\Service\Connection;

use Redis;
use RuntimeException;
use App\Entity\Server;

class RedisService implements ExecuteInterface, QueryInterface, ConnectionInterface
{
    private Server $server;
    private QueryService $query;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->query = new QueryService($server);
    }

    function getConnection(): Redis
    {
        $redis = new Redis();

        $redis->connect($this->server->getConIp(), $this->server->getConPort());
        $redis->auth(explode('@', $this->server->getConPassword()));

        return $redis;
    }

    public function execute($command, string $username = '', int $amount = 1): ?string
    {
        return $this->getConnection()->publish(
            $this->server->getServerName(),
            str_replace(['%player%', '%amount%'], [$username, $amount], $command)
        );
    }

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

    public function isPlayerLoggedIn(string $username): bool
    {
        try {
            return $this->query->isPlayerLoggedIn($username);
        } catch (RuntimeException $e) {
        }

        return str_contains($username, $this->execute('list'));
    }
}