<?php
namespace App\Service\Connection;

use App\Entity\Server;
use App\Repository\ServerRepository;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

class QueryService
{
    private ServerRepository $serverRepository;

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    public function getInfo(): ?array {
        try {
            return $this->getMinecraftQuery()->GetInfo() ? : [];
        } catch (MinecraftQueryException $e) {
            return [];
        }
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