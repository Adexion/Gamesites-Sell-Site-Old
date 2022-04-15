<?php

namespace App\Repository;

use App\Entity\ItemHistory;
use App\Entity\Payment;
use Doctrine\DBAL\Connection;
use App\Enum\PaymentStatusEnum;
use App\Enum\OperatorTypeEnum;
use DateTime;
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

    public function getCountOfBoughtItems(?DateTime $dateTime = null): int
    {
        $qb = $this->createQueryBuilder('ih')
            ->select('COUNT(1) AS count')
            ->where('ih.status = :status')
            ->setParameter(':status', PaymentStatusEnum::REALIZED);

        if ($dateTime) {
            !$qb->andWhere('ih.date >= :date')
                ->setParameter(':date', $dateTime->format('Y-m-d'));
        }

        return $qb->getQuery()->execute()[0]['count'];
    }

    public function getPaymentHash(ItemHistory $history): ?string
    {
       $query = $this->getEntityManager()->createQueryBuilder()
            ->from(Payment::class, 'p')
            ->select('p.hash')
            ->where('p.type = :type')
            ->andWhere('p.id = :id')
            ->setParameters([':type' => $history->getType(), ':id' => $history->getPaymentId()]
            )->getQuery();


       return $query->execute() ? $query->execute()[0]['hash'] : '';
    }

    public function getProgressOfTarget(): float
    {
        /** @var ItemHistory[] $result */
        $result = $this->createQueryBuilder('ih')
            ->select('ih')
            ->where('ih.date BETWEEN :from AND :to')
            ->andWhere('ih.type IN (:type)')
            ->andWhere('ih.status = :status')
            ->setParameter('from', new DateTime('first day of this month'))
            ->setParameter('to', new DateTime('last day of this month'))
            ->setParameter('type', OperatorTypeEnum::values(), Connection::PARAM_STR_ARRAY)
            ->setParameter('status', PaymentStatusEnum::REALIZED)
            ->getQuery()
            ->execute();

        foreach ($result as $itemHistory) {
            $count = $count ?? 0;
            $count += $itemHistory->getTotalPrice();
        }

        return $count ?? 0;
    }
}
