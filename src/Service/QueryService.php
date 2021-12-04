<?php

namespace App\Service;

use App\Entity\Configuration;
use App\Entity\Server;
use App\Repository\ConfigurationRepository;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;
use xPaw\SourceQuery\SourceQuery;

class QueryService
{
    private MinecraftQuery $queryMinecraft;
    private SourceQuery $sourceQuery;

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException */
    public function __construct(ConfigurationRepository $repository)
    {
        $config = $repository->findOneBy([]) ?? new Configuration();
        $server = $config->getDefaultServer() ?? new Server();

        error_reporting(E_ALL ^ E_WARNING);
        try {
            $this->sourceQuery = new SourceQuery();
            $this->sourceQuery->connect($server->getRConIp() ?? '', $server->getRConPort() ?? 0, 1);
            $this->sourceQuery->setRConPassword($server->getRConPassword());
        } catch (SocketException $exception) {
        }

        try {
            $this->queryMinecraft = new MinecraftQuery();
            $this->queryMinecraft->connect($config->getMinecraftQueryIp(), $config->getMinecraftQueryPort(), 1);
        } catch (MinecraftQueryException $exception) {
        }
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function execute($command, Server $server): ?string
    {
        $query = new SourceQuery();

        error_reporting(E_ALL ^ E_WARNING);
        try {
            $query->connect($server->getRConIp() ?? '', $server->getRConPort() ?? 0, 1);
            $query->setRConPassword($server->getRConPassword());
        } catch (SocketException $exception) {}

        return $query->rcon($command);
    }

    public function getPlayerList(): ?array
    {
        try {
            try {
                return $this->sourceQuery->GetPlayers();
            } catch (InvalidPacketException $e) {
                return $this->queryMinecraft->GetPlayers() ?: [];
            }
        } catch (SocketException $ignored) {
            return [];
        }
    }


    public function getInfo(): ?array
    {
        try {
            try {
                return $this->sourceQuery->GetInfo();
            } catch (InvalidPacketException $e) {
                return $this->queryMinecraft->GetInfo() ?: [];
            }
        } catch (SocketException $ignored) {
            return [];
        }
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        return array_search($username, $this->getPlayerList()) !== false;
    }

    public function disconnect()
    {
        $this->sourceQuery->disconnect();
    }
}