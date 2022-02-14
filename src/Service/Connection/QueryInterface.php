<?php

namespace App\Service\Connection;

interface QueryInterface
{
    public function getPlayerList(): ?array;

    public function getInfo(): ?array;

    public function isPlayerLoggedIn(string $username): bool;
}