<?php

namespace App\Service;

use App\Entity\Server;
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

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException|SocketException */
    public function execute($command, Server $server, string $username = ''): ?string
    {
        return $this->getRConConnect($server)->rcon(str_replace('%player%', $username, $command));
    }

    public function getPlayerList(): ?array
    {
        try {
            return $this->getMinecraftQuery()->GetPlayers() ?: [];
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
            return $this->getMinecraftQuery()->GetInfo() ?: [];
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
        return in_array($username, $this->getPlayerList());
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
        } catch (SocketException $e) {}

        return $sourceQuery;
    }

    /** @throws MinecraftQueryException */
    private function getMinecraftQuery(): MinecraftQuery
    {
        $server = $this->serverRepository->findOneBy(['isDefault' => true]) ?? new Server();

        $queryMinecraft = new MinecraftQuery();
        $queryMinecraft->connect($server->getMinecraftQueryIp(), $server->getMinecraftQueryPort());

        return $queryMinecraft;
    }
}