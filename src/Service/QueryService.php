<?php

namespace App\Service;

use App\Entity\Configuration;
use App\Entity\Server;
use App\Repository\ConfigurationRepository;
use App\Repository\ServerRepository;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;
use xPaw\SourceQuery\SourceQuery;

class QueryService
{
    private ServerRepository $serverRepository;
    private ConfigurationRepository $configurationRepository;

    public function __construct(ConfigurationRepository $configurationRepository, ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
        $this->configurationRepository = $configurationRepository;
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function execute($command, Server $server): ?string
    {
        return $this->getRConConnect($server)->rcon($command);
    }

    /** @throws AuthenticationException|InvalidArgumentException */
    public function getPlayerList(): ?array
    {
        try {
            try {
                return $this->getRConConnect()->GetPlayers();
            } catch (InvalidPacketException $e) {
                return $this->getMinecraftQuery()->GetPlayers() ?: [];
            }
        } catch (SocketException $ignored) {
            return [];
        }
    }

    /** @throws AuthenticationException|InvalidArgumentException */
    public function getInfo(): ?array
    {
        try {
            try {
                return $this->getRConConnect()->GetInfo();
            } catch (InvalidPacketException $e) {
                return $this->getMinecraftQuery()->GetInfo() ?: [];
            }
        } catch (SocketException $ignored) {
            return [];
        }
    }

    /** @throws AuthenticationException|InvalidArgumentException */
    public function isPlayerLoggedIn(string $username): bool
    {
        return array_search($username, $this->getPlayerList()) !== false;
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException */
    private function getRConConnect(Server $server = null): SourceQuery
    {
        $server = $server ?? $this->serverRepository->findOneBy(['isDefault' => true]) ?? new Server();
        $sourceQuery = new SourceQuery();

        error_reporting(E_ALL ^ E_WARNING);
        try {
            $sourceQuery->connect($server->getRConIp() ?? '', $server->getRConPort() ?? 0, 1);
            $sourceQuery->setRConPassword($server->getRConPassword());
        } catch (SocketException $exception) {}

        return $sourceQuery;
    }

    private function getMinecraftQuery(): MinecraftQuery
    {
        $config = $this->configurationRepository->findOneBy([]) ?? new Configuration();

        $queryMinecraft = new MinecraftQuery();
        try {
            $queryMinecraft->connect($config->getMinecraftQueryIp(), $config->getMinecraftQueryPort(), 1);
        } catch (MinecraftQueryException $exception) {}

        return $queryMinecraft;
    }
}