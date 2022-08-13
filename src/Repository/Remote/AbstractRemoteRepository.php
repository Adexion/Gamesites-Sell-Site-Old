<?php

namespace App\Repository\Remote;

use App\Entity\AbstractRemoteEntity;
use App\Enum\DatabaseTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRemoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    protected function getConnectionByEntity(?AbstractRemoteEntity $entity): ?RemoteRepositoryInterface
    {
        if (!$entity) {
            return null;
        }

        $class = $entity->getDatabaseType() === DatabaseTypeEnum::MongoDB
            ? 'Mongo'
            : 'SQL';

        $className = "\App\Repository\Remote\\{$class}RemoteRepository";

        return new $className($entity);
    }

    protected abstract function findRemote(array $criteria = [], array $filters = []): ?array;

    protected function con(array $criteria = [], ?string $name = null, ?int $limit = null): array
    {
        /** @var ?AbstractRemoteEntity $entity */
        $entities = $this->findBy($criteria);

        foreach ($entities as $entity) {
            $con[$entity->getDisplayName()]['result'] = $this->getConnectionByEntity($entity)->find($name, $limit);
            $con[$entity->getDisplayName()]['entity'] = $this->getConnectionByEntity($entity)->find($name, $limit);
        }

        return array_filter($con ?? []);
    }
}