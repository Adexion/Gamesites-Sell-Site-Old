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
class RankRepository extends AbstractRemoteRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }
}
