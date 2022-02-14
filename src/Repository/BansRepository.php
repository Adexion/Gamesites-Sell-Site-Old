<?php

namespace App\Repository;

use App\Entity\Bans;
use App\Entity\Rank;
use Doctrine\DBAL\Driver;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    /** @throws Driver\Exception */
    public function findRemote(array $criteria = [], array $filters = []): array
    {
        if (!$bans = $this->findOneBy($criteria)) {
            return [];
        }

        try {
            return $this->createQB($bans)
                ->addSelect('x.' . $bans->getColumnTwo() . ' AS reason')
                ->setMaxResults(self::MAX_RESULT)
                ->execute()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}
