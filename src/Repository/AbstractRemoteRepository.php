<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractRemoteRepository extends ServiceEntityRepository
{
    public function findConnectionDataByType(string $type): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r.login', 'r.password', 'r.ip', 'r.port', 'r.database')
            ->where('r.type = :type')
            ->setParameter(':type', $type)
            ->getQuery()
            ->execute();
    }
}