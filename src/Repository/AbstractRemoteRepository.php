<?php

namespace App\Repository;

use App\Entity\AbstractRemoteEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRemoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /** @throws Exception */
    protected function connect(AbstractRemoteEntity $entity): Connection
    {
        return DriverManager::getConnection([
            'url' => sprintf('mysql://%s:%s@%s:%s/%s?serverVersion=13&charset=utf8', ...array_values($entity->toArray())),
        ], new Configuration());
    }

    /** @throws Exception */
    public abstract function findRemote(array $criteria = [], array $filters = []): ?array;

    /** @throws Exception */
    protected function createQB(AbstractRemoteEntity $entity = null): QueryBuilder
    {
        return $this->connect($entity)->createQueryBuilder()
            ->select('x.`'.$entity->getName().'` AS name', 'x.`'.$entity->getColumnOne().'` AS value')
            ->from($entity->getDirectory(), 'x')
            ->orderBy('x.`'.$entity->getColumnOne().'`', 'DESC');
    }
}