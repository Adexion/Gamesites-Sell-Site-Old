<?php

namespace App\Controller\Client;

use App\Enum\RankEnum;
use App\Repository\AdministrationRepository;
use App\Repository\GuildItemRepository;
use App\Repository\ItemHistoryRepository;
use App\Repository\RankRepository;
use App\Repository\ServerRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\SyntaxError;

class MainController extends AbstractRenderController
{
    /**
     * @Route(path="/", name="index")
     * @throws SyntaxError
     */
    public function index(
        GuildItemRepository $guildItemRepository,
        AdministrationRepository $administrationRepository,
        ItemHistoryRepository $itemHistoryRepository,
        ServerRepository $serverRepository,
        RankRepository $rankRepository,
        Request $request
    ): Response {
        return $this->render('client/index.html.twig', [
            'guildItem' => $guildItemRepository->findAll(),
            'administration' => $administrationRepository->findBy([], ['priority' => 'ASC']),
            'boughtToday' => $itemHistoryRepository->getCountOfBoughtItems(new DateTime()),
            'boughtAll' => $itemHistoryRepository->getCountOfBoughtItems(),
            'serverList' => array_slice($serverRepository->findAll(), 0, 3),
            'guildRank' => $rankRepository->findRemote(['type' => RankEnum::GUILD]),
            'playerRank' => $rankRepository->findRemote(['type' => RankEnum::PLAYER], $request->query->all()),
        ]);
    }
}