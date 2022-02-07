<?php

namespace App\Repository;

use App\Entity\PaySafeCardVoucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /** @throws ORMException|OptimisticLockException */
    public function insertOrUpdate(PaySafeCardVoucher $history): PaySafeCardVoucher
    {
        $this->_em->persist($history);
        $this->_em->flush();

        return $history;
    }
}
