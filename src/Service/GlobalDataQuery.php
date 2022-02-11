<?php

namespace App\Service;

use App\Entity\Additional;
use App\Entity\Bans;
use App\Entity\Configuration;
use App\Entity\Rank;
use App\Entity\Server;
use App\Enum\RankEnum;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class GlobalDataQuery
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getGlobals(): array
    {
       return $this->entityManager->createQueryBuilder()
            ->select(
                'c.logo', 'c.ip AS serverIp', 'c.description', 'c.simplePaySafeCard', 'c.target, c.serverName',
                's.minecraftQueryIp', 's.minecraftQueryPort',
                'a.siteTitle',  'a.mainText', 'a.mainDescription', 'a.trailerText',  'a.guildText', 'a.discord',
                'a.ts3',  'a.facebook', 'a.yt', 'a.instagram',  'a.tiktok', 'a.trailer',
                'r1.id AS guild', 'r2.id AS player', 'b.id AS bans'
            )
           ->from(Configuration::class, 'c')
           ->leftJoin(Additional::class, 'a', 'WITH', 'a.id IS NOT NULL')
           ->leftJoin(Server::class, 's', 'WITH', 's.isDefault = true')
           ->leftJoin(Rank::class, 'r1', 'WITH', 'r1.type = :guild')
           ->leftJoin(Rank::class, 'r2', 'WITH', 'r2.type = :player')
           ->leftJoin(Bans::class, 'b', 'WITH', 'b.id IS NOT NULL')
           ->setParameters([
               ':guild' => RankEnum::GUILD,
               ':player' => RankEnum::PLAYER
           ])
           ->where('1 = 1')
           ->getQuery()
           ->execute()[0];
    }
}