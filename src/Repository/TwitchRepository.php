<?php

namespace App\Repository;

use App\Entity\TwitchStreamer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TwitchStreamer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TwitchStreamer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TwitchStreamer[]    findAll()
 * @method TwitchStreamer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwitchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TwitchStreamer::class);
    }
}
