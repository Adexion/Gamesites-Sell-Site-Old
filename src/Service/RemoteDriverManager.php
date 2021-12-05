<?php

namespace App\Service;

use App\Enum\RankEnum;
use App\Repository\AbstractRemoteRepository;
use App\Repository\RankRepository;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use InvalidArgumentException;

class RemoteDriverManager
{
    /** @throws Exception */
    public function getConnection(int $type, AbstractRemoteRepository $repository): Connection
    {
        if (!RankEnum::isValid($type)) {
            throw new InvalidArgumentException('Given wrong type');
        }

        $rank = $repository->findConnectionDataByType($type)[0];
        return DriverManager::getConnection([
            'url' => sprintf('mysql://%s:%s@%s:%s/%s?serverVersion=13&charset=utf8', ...array_values($rank))
        ], new Configuration());
    }
}