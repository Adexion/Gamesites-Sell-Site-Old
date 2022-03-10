<?php

namespace App\Repository;

use App\Entity\Rank;
use App\Repository\Remote\AbstractRemoteRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankRepository extends AbstractRemoteRepository
{
    private const MAX_RESULT = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }

    public function findRemote(array $criteria = [], array $filters = []): ?array
    {
        return $this->con($criteria, $filters['name'] ?? '', self::MAX_RESULT);
    }
}
