<?php

namespace App\Extension;

use App\Entity\Additional;
use App\Entity\Configuration;
use App\Enum\RankEnum;
use App\Repository\AdditionalRepository;
use App\Repository\ConfigurationRepository;
use App\Service\QueryService;
use App\Service\Rank\RankService;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private AdditionalRepository $additionalRepository;
    private QueryService $queryService;
    private ConfigurationRepository $configurationRepository;
    private RankService $rankService;
    private ?Request $request;

    public function __construct(
        AdditionalRepository $additionalRepository,
        ConfigurationRepository $configurationRepository,
        QueryService $queryService,
        RankService $rankService,
        RequestStack $requestStack
    ) {
        $this->additionalRepository = $additionalRepository;
        $this->configurationRepository = $configurationRepository;
        $this->queryService = $queryService;
        $this->rankService = $rankService;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getGlobals(): array
    {
        $additional = $this->additionalRepository->findOneBy([]) ?? (new Additional());
        $configuration = $this->configurationRepository->findOneBy([]) ?? (new Configuration())
            ->setLogo('/assets/images/minecraft.png')
            ->setServerName('A Minecraft Server');

        return
            $additional->toArray() + [
                'serverInfo' => $this->queryService->getInfo(),
                'logo' => $configuration->getLogo(),
                'serverName' => $configuration->getServerName(),
                'guildRank' => $this->rankService->getRank(RankEnum::GUILD),
                'playerRank' => $this->rankService->getRank(RankEnum::PLAYER, $this->request->query->get('name'))
            ];
    }
}