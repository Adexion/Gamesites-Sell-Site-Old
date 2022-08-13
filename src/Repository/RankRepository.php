<?php

namespace App\Repository;

use Exception;
use App\Entity\Rank;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Remote\AbstractRemoteRepository;

/**
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankRepository extends AbstractRemoteRepository
{
    private const MAX_RESULT = 8;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }

    public function findRemote(array $criteria = [], array $filters = []): ?array
    {
        try {
            return $this->con($criteria, $filters['name'] ?? '', self::MAX_RESULT);
        } catch (Exception $exception) {
            return [];
        }
    }
}
