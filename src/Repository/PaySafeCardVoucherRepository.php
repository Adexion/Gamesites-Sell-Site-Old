<?php

namespace App\Repository;

use App\Entity\PaySafeCardVoucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaySafeCardVoucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaySafeCardVoucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaySafeCardVoucher[]    findAll()
 * @method PaySafeCardVoucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaySafeCardVoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaySafeCardVoucher::class);
    }

    // /**
    //  * @return PaySafeCardVoucher[] Returns an array of PaySafeCardVoucher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaySafeCardVoucher
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
