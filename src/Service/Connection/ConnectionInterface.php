<?php

namespace App\Service\Connection;

use App\Entity\Server;

interface ConnectionInterface
{
    public function __construct(Server $server);

    function getConnection();
}