<?php

namespace App\Service;

use App\Entity\Configuration;
use App\Entity\Server;
use App\Repository\ConfigurationRepository;
use App\Repository\ServerRepository;
use Exception;
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

    public function getPlayerList(): ?array
    {
        try {
            return $this->getMinecraftQuery()->GetPlayers();
        } catch (Exception $e) {
            try {
                return $this->getRConConnect()->GetInfo();
            } catch (Exception $e) {
                return [];
            }
        }
    }

    public function getInfo(): ?array
    {
        try {
            return $this->getMinecraftQuery()->GetInfo();
        } catch (Exception  $e) {
            try {
                return $this->getRConConnect()->GetInfo();
            } catch (Exception $e) {
                return [];
            }
        }
    }

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
        } catch (SocketException $e) {
        }

        return $sourceQuery;
    }

    private function getMinecraftQuery(): MinecraftQuery
    {
        $config = $this->configurationRepository->findOneBy([]) ?? new Configuration();
        $queryMinecraft = new MinecraftQuery();

        $queryMinecraft->connect($config->getMinecraftQueryIp(), $config->getMinecraftQueryPort());

        return $queryMinecraft;
    }
}