<?php

namespace App\Repository\Remote;

use App\Entity\AbstractRemoteEntity;
use App\Enum\DatabaseTypeEnum;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class SQLRemoteRepository implements RemoteRepositoryInterface
{
    private Connection $con;
    private AbstractRemoteEntity $entity;

    /**
     * @throws Exception
     */
    public function __construct(AbstractRemoteEntity $entity)
    {
        $this->entity = $entity;
        $this->con = DriverManager::getConnection([
            'url' => sprintf(
                strtolower(DatabaseTypeEnum::from($entity->getDatabaseType())->getKey())
                . '://%s:%s@%s:%s/%s',
                ...array_values($entity->toArray())
            ),
        ], new Configuration());
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function find(?string $name = null, ?int $limit = null): array
    {
        $mapped = implode(
            ',',
            array_map(function ($key, $value) {
                return " x.$value AS \"$key\"";
            }, array_keys($this->entity->getSearchFields()), array_values($this->entity->getSearchFields()))
        );

        $sql = "SELECT{$mapped} FROM {$this->entity->getDirectory()} x";

        if ($name) {
            $sql .= " WHERE `name` = \"{$name}\"";
        }

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $this->con->executeQuery($sql)->fetchAllAssociative();
    }
}