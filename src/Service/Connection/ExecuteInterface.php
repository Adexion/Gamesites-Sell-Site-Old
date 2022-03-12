<?php

namespace App\Service\Connection;

interface ExecuteInterface
{
    public function execute($command, string $username = '', int $amount =1): ?string;
}