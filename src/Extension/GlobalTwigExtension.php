<?php

namespace App\Extension;

use Exception;
use App\Entity\Server;
use App\Service\GlobalDataQuery;
use App\Repository\MetaRepository;
use App\Repository\HeadRepository;
use App\Repository\ServerRepository;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use App\Service\Connection\QueryService;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private GlobalDataQuery $globalDataQuery;
    private HeadRepository $headRepository;
    private QueryService $queryService;

    public function __construct(GlobalDataQuery $globalDataQuery, ServerRepository $serverRepository, HeadRepository $headRepository)
    {
        $this->globalDataQuery = $globalDataQuery;
        $this->headRepository = $headRepository;
        $this->queryService = new QueryService($serverRepository->findOneBy(['isDefault' => true]) ?? new Server());
    }

    /** @throws Exception */
    public function getGlobals(): array
    {
        $globals = $this->globalDataQuery->getGlobals();

        return [
            'serverInfo' => $globals['minecraftQueryIp'] ?? null ? $this->queryService->getInfo() : [],
            'isPlayerRank' => $globals['player'] ?? null,
            'isGuildRank' => $globals['guild'] ?? null,
            'serverIp' => $globals['serverIp'] ?? null,
            'logo' => $globals['logo'] ?? null,
            'background' => $globals['background'] ?? null,
            'target' => $globals['target'] ?? null,
            'serverName' => $globals['serverName'] ?? null,
            'serverDescription' => $globals['description'] ?? null,
            'areBansSet' => $globals['bans'] ?? null,
            'simplePaySafeCard' => $globals['simplePaySafeCard'] ?? null,
            'showBigLogo' => $globals['showBigLogo'] ?? null,
            'siteTitle' => $globals['siteTitle'] ?? null,
            'mainText' => $globals['mainText'] ?? null,
            'mainDescription' => $globals['mainDescription'] ?? null,
            'trailerText' => $globals['trailerText'] ?? null,
            'guildText' => $globals['guildText'] ?? null,
            'discord' => $globals['discord'] ?? null,
            'instagram' => $globals['instagram'] ?? null,
            'yt' => $globals['yt'] ?? null,
            'ts3' => $globals['ts3'] ?? null,
            'facebook' => $globals['facebook'] ?? null,
            'tiktok' => $globals['tiktok'] ?? null,
            'trailer' => $globals['trailer'] ?? null,
            'head' => $this->headRepository->findAll(),
        ];
    }
}