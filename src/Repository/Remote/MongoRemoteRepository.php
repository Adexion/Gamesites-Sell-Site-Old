<?php

namespace App\Repository\Remote;

use App\Entity\AbstractRemoteEntity;
use MongoDB\Client;
use MongoDB\Collection;

class MongoRemoteRepository implements RemoteRepositoryInterface
{
    private Collection $con;
    private AbstractRemoteEntity $entity;

    public function __construct(AbstractRemoteEntity $entity)
    {
        $this->entity = $entity;

        $this->con = (new Client(
            "mongodb://{$entity->getLogin()}:{$entity->getPassword()}@{$entity->getIp()}:{$entity->getPort()}/"
        ))->selectDatabase($entity->getDatabase())->selectCollection($entity->getDirectory());
    }

    public function find(?string $name = null, ?int $limit = null): array
    {
        if ($name) {
            $name = [
                $this->entity->getName() => $name,
            ];
        }
        if ($limit) {
            $limit = [
                'limit' => $limit,
            ];
        }

        $result = $this->con->find($name ?? [], $limit ?? [])->toArray();
        foreach ($result as $found) {
            $element = [];
            foreach ($this->entity->getSearchFields() as $key => $value) {
                $element[$key] = $found[$value];
            }

            $response[] = $element;
        }

        return $response ?? [];
    }
}