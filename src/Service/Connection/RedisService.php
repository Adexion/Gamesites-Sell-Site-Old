<?php
namespace App\Service\Connection;

use App\Repository\ServerRepository;

class RedisService
{
    private ServerRepository $serverRepository;

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }
}