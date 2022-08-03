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
            return new QueryService($server);
        }

        return new $class($server);
    }
}