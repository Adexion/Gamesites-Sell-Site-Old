<?php

namespace App\Repository;

use App\Entity\ItemHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemHistory[]    findAll()
 * @method ItemHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemHistory::class);
    }

    /** @throws ORMException|OptimisticLockException */
    public function insertOrUpdate(ItemHistory $history): ItemHistory
    {
        $this->_em->persist($history);
        $this->_em->flush();

        return $history;
    }
}
