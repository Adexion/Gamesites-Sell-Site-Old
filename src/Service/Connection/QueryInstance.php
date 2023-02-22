<?php

namespace App\Service\Connection;

use RuntimeException;
use xPaw\MinecraftPing;
use xPaw\MinecraftQuery;
use xPaw\MinecraftPingException;
use xPaw\MinecraftQueryException;

class QueryInstance
{
    private MinecraftQuery $query;
    private MinecraftPing $ping;

    public function connect(?string $serverIp, ?string $serverPort)
    {
        $this->query = new MinecraftQuery();
        if (!$serverIp || !$serverPort) {
            return;
        }

        if (!$this->isServerResponding($serverIp, $serverPort)) {
            return;
        }

        try {
            $this->query->connect($serverIp, $serverPort, 1);
        } catch (MinecraftQueryException $exception) {
        }

        try {
            $this->ping = new MinecraftPing($serverIp, $serverPort, 2);
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

    private function isServerResponding(string $url = 'localhost', string $port = "25565"): bool
    {
        return (bool)@fsockopen($url, $port, $code, $message, 0.5);
    }
}