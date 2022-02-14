<?php

namespace App\Extension;

use App\Service\Connection\QueryService;
use App\Service\GlobalDataQuery;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private GlobalDataQuery $globalDataQuery;
    private QueryService $queryService;

    public function __construct(GlobalDataQuery $globalDataQuery, QueryService $queryService)
    {
        $this->globalDataQuery = $globalDataQuery;
        $this->queryService = $queryService;
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
            'target' => $globals['target'] ?? null,
            'serverName' => $globals['serverName'] ?? null,
            'serverDescription' => $globals['description'] ?? null,
            'areBansSet' => $globals['bans'] ?? null,
            'simplePaySafeCard' => $globals['simplePaySafeCard'] ?? null,
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
        ];
    }
}