<?php

namespace App\Service;

use App\Repository\RankRepository;
use Doctrine\DBAL\Exception;

class RankService
{
    private RemoteDriverManager $manager;
    private RankRepository $repository;

    private const MAX_RESULT = 10;

    public function __construct(RemoteDriverManager $manager, RankRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /** @throws Exception */
    public function getRank(int $type, $value = null): ?array
    {
        $rank = $this->repository->findOneBy(['type' => $type]);
        if (!$rank) {
            return [];
        }

        $db = $this->manager->getConnection($type, $this->repository);
        $qb = $db->createQueryBuilder()
            ->select('x.`'.$rank->getName().'` AS name', 'x.`'.$rank->getPoint().'` AS point')
            ->from($rank->getDirectory(), 'x')
            ->orderBy('x.`'.$rank->getPoint().'`', 'DESC')
            ->setMaxResults(self::MAX_RESULT);

        if ($value) {
            $qb
                ->where($rank->getName().' = :value')
                ->setParameter('value', $value);
        }

        return $qb
            ->executeQuery()
            ->fetchAllAssociative();
    }
}