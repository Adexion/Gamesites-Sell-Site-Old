<?php

namespace App\Repository\Remote;

use App\Entity\AbstractRemoteEntity;

interface RemoteRepositoryInterface
{
    public function __construct(AbstractRemoteEntity $entity);

    public function find(?string $name = null, ?int $limit = null): array;
}