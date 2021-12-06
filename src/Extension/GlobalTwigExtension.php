<?php

namespace App\Extension;

use App\Entity\Additional;
use App\Entity\Configuration;
use App\Enum\RankEnum;
use App\Repository\AdditionalRepository;
use App\Repository\BansRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\RankRepository;
use App\Service\QueryService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private AdditionalRepository $additionalRepository;
    private QueryService $queryService;
    private ConfigurationRepository $configurationRepository;
    private RankRepository $rankRepository;
    private ?Request $request;
    private BansRepository $bansRepository;

    public function __construct(
        AdditionalRepository $additionalRepository,
        ConfigurationRepository $configurationRepository,
        QueryService $queryService,
        RankRepository $rankRepository,
        BansRepository $bansRepository,
        RequestStack $requestStack
    ) {
        $this->additionalRepository = $additionalRepository;
        $this->configurationRepository = $configurationRepository;
        $this->queryService = $queryService;
        $this->rankRepository = $rankRepository;
        $this->bansRepository = $bansRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    /** @throws Exception */
    public function getGlobals(): array
    {
        $additional = $this->additionalRepository->findOneBy([]) ?? (new Additional());
        $configuration = $this->configurationRepository->findOneBy([]) ?? (new Configuration())
                ->setLogo('minecraft.png')
                ->setServerName('A Minecraft Server');

        return
            $additional->toArray() + [
                'serverInfo' => $this->queryService->getInfo(),
                'logo' => $configuration->getLogo(),
                'serverName' => $configuration->getServerName(),
                'guildRank' => $this->rankRepository->findRemote(['type' => RankEnum::GUILD]),
                'playerRank' => $this->rankRepository->findRemote(['type' => RankEnum::PLAYER], $this->request->query->all()),
                'areBansSet' => (bool)$this->bansRepository->findOneBy([])
            ];
    }
}