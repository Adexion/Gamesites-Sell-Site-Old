<?php

namespace App\Service\Connection;

interface ExecuteInterface
{
    public function execute($command, string $username = ''): ?string;
}