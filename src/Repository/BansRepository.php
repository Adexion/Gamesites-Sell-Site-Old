<?php

namespace App\Repository;

use App\Entity\Bans;
use App\Entity\Rank;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BansRepository extends AbstractRemoteRepository
{
    private const MAX_RESULT = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bans::class);
    }

    /** @throws Exception */
    public function findRemote(array $criteria = [], array $filters = []): ?array
    {
        $bans = $this->findOneBy($criteria);

        return $this->createQB($bans)
            ->addSelect('x.'. $bans->getColumnTwo().' AS reason')
            ->setMaxResults(self::MAX_RESULT)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
