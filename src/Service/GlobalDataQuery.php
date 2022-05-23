<?php

namespace App\Service;

use Exception;
use App\Entity\Rank;
use App\Enum\RankEnum;
use App\Entity\Server;
use App\Entity\Additional;
use App\Entity\Configuration;
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
        $query = $this->entityManager->createQueryBuilder()
            ->select(
                'c.logo',
                'c.background',
                'c.ip AS serverIp',
                'c.simplePaySafeCard',
                'c.showBigLogo',
                'c.target',
                's.minecraftQueryIp',
                's.minecraftQueryPort',
                'a.siteName',
                'a.mainText',
                'a.mainDescription',
                'a.trailerText',
                'a.guildText',
                'a.discord',
                'a.ts3',
                'a.facebook',
                'a.yt',
                'a.instagram',
                'a.tiktok',
                'a.trailer',
                'r1.id AS guild',
                'r2.id AS player'
            )
            ->from(Configuration::class, 'c')
            ->leftJoin(Additional::class, 'a', 'WITH', 'a.id IS NOT NULL')
            ->leftJoin(Server::class, 's', 'WITH', 's.isDefault = true')
            ->leftJoin(Rank::class, 'r1', 'WITH', 'r1.type = :dark')
            ->leftJoin(Rank::class, 'r2', 'WITH', 'r2.type = :player')
            ->setParameters([
                ':dark' => RankEnum::GUILD,
                ':player' => RankEnum::PLAYER,
            ])
            ->where('1 = 1')
            ->getQuery();

        try {
            return $query->execute() ? $query->execute()[0] : [];
        } catch (Exception $exception) {
            return [];
        }
    }
}