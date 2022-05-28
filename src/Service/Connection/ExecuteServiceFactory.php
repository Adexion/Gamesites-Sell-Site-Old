<?php

namespace App\Service\Connection;

use App\Entity\Server;
use RuntimeException;

class ExecuteServiceFactory
{
    /** @return ExecuteInterface|QueryInterface|ConnectionInterface|ConsoleInterface */
    public function getExecutionService(Server $server)
    {
        $class = 'App\Service\Connection\\' . ucfirst($server->getConnectionType()) . 'Service';

        if (!class_exists($class)) {
            throw new RuntimeException('Class ' . ucfirst($server->getConnectionType()) . ' does not exist');
        }

        return new $class($server);
    }
}