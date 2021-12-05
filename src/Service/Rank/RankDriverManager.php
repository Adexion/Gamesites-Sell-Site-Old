<?php

namespace App\Service\Rank;

use App\Enum\RankEnum;
use App\Repository\RankRepository;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use InvalidArgumentException;

class RankDriverManager
{
    private RankRepository $repository;

    public function __construct(RankRepository $repository)
    {
        $this->repository = $repository;
    }

    /** @throws Exception */
    public function getConnection(int $type): Connection
    {
        if (!RankEnum::isValid($type)) {
            throw new InvalidArgumentException('Given wrong type');
        }

        return DriverManager::getConnection([
            'url' => sprintf('mysql://%s:%s@%s:%s/%s?serverVersion=13&charset=utf8', ...$this->repository->findConnectionDataByType($type)[0])
        ], new Configuration());
    }
}