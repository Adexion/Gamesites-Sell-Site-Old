<?php

namespace App\Service\Connection;

use xPaw\MinecraftPing;
use xPaw\MinecraftQuery;
use xPaw\MinecraftPingException;
use xPaw\MinecraftQueryException;
use RuntimeException;

class QueryInstance
{
    private MinecraftQuery $query;
    private MinecraftPing $ping;

    public function connect(?string $serverIp, ?string $serverPort)
    {
        $this->query = new MinecraftQuery();
        return;
        try {
            $this->query->connect($serverIp, $serverPort, 0.1);
        } catch (MinecraftQueryException $exception) {
        }

        try {
            $this->ping = new MinecraftPing($serverIp, $serverPort, 0.1);
            $this->ping->connect();
        } catch (MinecraftPingException $exception) {
        }
    }

    public function getInfo(): array
    {
        $response = $this->query->GetInfo();
        if ($response) {
            return $response;
        }

        try {
            if ($this->ping ?? false) {
                $response = $this->ping->Query();
            }
        } catch (MinecraftPingException $ignored) {
        }

        if (!($response ?? false)) {
            return [];
        }

        return [
            'Players' => $response['players']['online'] ?? '0',
            'MaxPlayers' => $response['players']['max'] ?? '0',
        ];
    }

    public function getPlayers(): array
    {
        $response = $this->query->GetPlayers();
        if ($response) {
            return $response;
        }

        throw new RuntimeException('Players can not be getting by this way');
    }
}