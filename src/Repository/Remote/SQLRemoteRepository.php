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
            array_map(
                fn($key, $value) => "x.$value AS \"$key\"",
                array_keys($this->entity->getSearchFields()),
                array_values($this->entity->getSearchFields())
            )
        );

        if (!empty($this->entity->getAdditionalFields())) {
            $names = array_map(fn($value) => $value['name'], $this->entity->getAdditionalFields());
            $searches = array_map(fn($value) => $value['search'], $this->entity->getAdditionalFields());

            $fields = implode(',', array_map(fn($key, $value) => "x.$value AS \"$key\"", $names, $searches));
            $mapped .= ',' . $fields;
        }

        $sql = "SELECT {$mapped} FROM {$this->entity->getDirectory()} x";

        if ($name) {
            $sql .= " WHERE `name` = \"{$name}\"";
        }

        $sql .= ' ORDER BY x.' . $this->entity->getOrderBy() . ' DESC';

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        return $this->con->executeQuery($sql)->fetchAllAssociative();
    }
}