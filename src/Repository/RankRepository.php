<?php

namespace App\Repository;

use App\Entity\Rank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }

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
