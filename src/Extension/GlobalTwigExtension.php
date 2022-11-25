<?php

namespace App\Extension;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Exception;
use App\Entity\Head;
use App\Entity\Bans;
use App\Entity\Server;
use App\Form\DevTemplateType;
use App\Service\GlobalDataQuery;
use App\Repository\HeadRepository;
use App\Repository\BansRepository;
use Twig\Extension\GlobalsInterface;
use Twig\Extension\AbstractExtension;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Connection\QueryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Service\Connection\ExecuteServiceFactory;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private GlobalDataQuery $globalDataQuery;
    private HeadRepository $headRepository;
    private QueryInterface $queryService;
    private FormFactoryInterface $factory;
    private BansRepository $bansRepository;
    private ArticleRepository $articleRepository;

    public function __construct(
        GlobalDataQuery $globalDataQuery,
        FormFactoryInterface $factory,
        ManagerRegistry $managerRegistry,
        ExecuteServiceFactory $executeServiceFactory
    ) {
        $this->globalDataQuery = $globalDataQuery;
        $this->factory = $factory;

        $serverRepository = $managerRegistry->getRepository(Server::class);
        $this->headRepository = $managerRegistry->getRepository(Head::class);
        $this->bansRepository = $managerRegistry->getRepository(Bans::class);
        $this->articleRepository = $managerRegistry->getRepository(Article::class);

        $this->queryService = $executeServiceFactory->getExecutionService($serverRepository->getDefault());
    }

    /** @throws Exception|TransportExceptionInterface */
    public function getGlobals(): array
    {
        $globals = $this->globalDataQuery->getGlobals();

        return [
            'serverInfo' => $this->queryService->getInfo() ?? [],
            'isPlayerRank' => (bool)($globals['player'] ?? null),
            'isGuildRank' => (bool)($globals['guild'] ?? null),
            'serverIp' => $globals['serverIp'] ?? null,
            'logo' => $globals['logo'] ?? null,
            'background' => $globals['background'] ?? null,
            'target' => $globals['target'] ?? null,
            'siteName' => $globals['siteName'] ?? null,
            'serverDescription' => $globals['description'] ?? null,
            'areBansSet' => (bool)($this->bansRepository->findRemote() ?? null),
            'bansCount' => count($this->bansRepository->findRemote()) ?? null,
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
            'head' => $this->headRepository->findOneBy([]),
            'templates' => $this->factory->create(DevTemplateType::class)->createView(),
            'development' => isset($_ENV['APP_DEV']) && $_ENV['APP_DEV'] == 'development',
            'isAnyArticle' => $this->articleRepository->findOneBy([])
        ];
    }
}