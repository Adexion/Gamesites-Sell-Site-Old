<?php

namespace App\Repository;

use App\Entity\Additional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Additional|null find($id, $lockMode = null, $lockVersion = null)
 * @method Additional|null findOneBy(array $criteria, array $orderBy = null)
 * @method Additional[]    findAll()
 * @method Additional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdditionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Additional::class);
    }
}
