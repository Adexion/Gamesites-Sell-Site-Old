<?php

namespace App\Service;

use App\Entity\Configuration;
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
    private SourceQuery $queryAll;

    /** @throws InvalidPacketException|AuthenticationException|InvalidArgumentException */
    public function __construct(ConfigurationRepository $repository)
    {
        /** @var Configuration $config */
        $config = $repository->findOneBy([]) ?? new Configuration();

        error_reporting(E_ALL ^ E_WARNING);
        try {
            $this->queryAll = new SourceQuery();
            $this->queryAll->connect($config->getRConIp() ?? '', $config->getRConPort() ?? 0);
            $this->queryAll->setRConPassword($config->getRConPassword());
        } catch (SocketException $exception) {
        }

        try {
            $this->queryMinecraft = new MinecraftQuery();
            $this->queryMinecraft->connect($config->getMinecraftQueryIp(), $config->getMinecraftQueryPort());
        } catch (MinecraftQueryException $exception) {
        }
    }

    /** @throws SocketException|InvalidPacketException|AuthenticationException */
    public function execute($command): ?string
    {
        return $this->queryAll->rcon($command);
    }

    /** @throws SocketException */
    public function getPlayerList(): ?array
    {
        try {
            try {
                return $this->queryAll->GetPlayers();
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
                return $this->queryAll->GetInfo();
            } catch (InvalidPacketException $e) {
                return $this->queryMinecraft->GetInfo() ?: [];
            }
        } catch (SocketException $ignored) {
            return [];
        }
    }

    /** @throws SocketException */
    public function isPlayerLoggedIn(string $username): bool
    {
        return array_search($username, $this->getPlayerList()) !== false;
    }

    public function disconnect()
    {
        $this->queryAll->disconnect();
    }
}