<?php

namespace App\Repository;

use App\Entity\PaySafeCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaySafeCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaySafeCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaySafeCard[]    findAll()
 * @method PaySafeCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaySafeCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaySafeCard::class);
    }

    /** @throws ORMException|OptimisticLockException */
    public function insertOrUpdate(PaySafeCard $history): PaySafeCard
    {
        $this->_em->persist($history);
        $this->_em->flush();

        return $history;
    }
}
