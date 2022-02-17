<?php

namespace App\Repository;

use App\Entity\Rank;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\DriverException;
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

    /** @throws Exception|\Doctrine\DBAL\Driver\Exception */
    public function findRemote(array $criteria = [], array $filters = []): ?array
    {
        $rank = $this->findOneBy($criteria);
        if (!$rank) {
            return [];
        }

        $qb = $this->createQB($rank)
            ->setMaxResults(self::MAX_RESULT);

        if (isset($criteria['name'])) {
            $qb
                ->where($rank->getName() . ' = :value')
                ->setParameter('value', $criteria['name']);
        }

        try {
            return $qb
                ->execute()
                ->fetchAllAssociative();
        } catch (DriverException $e) {
            return [];
        }
    }
}
