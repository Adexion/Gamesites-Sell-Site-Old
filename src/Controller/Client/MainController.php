<?php

namespace App\Controller\Client;

use App\Repository\GuildItemRepository;
use App\Repository\ItemHistoryRepository;
use App\Repository\ServerRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractRenderController
{
    /** @Route(path="/", name="index") */
    public function index(
        GuildItemRepository $guildItemRepository,
        UserRepository $userRepository,
        ItemHistoryRepository $itemHistoryRepository,
        ServerRepository $serverRepository
    ): Response
    {
        return $this->render('client/index.html.twig', [
            'guildItem' => $guildItemRepository->findBy([]),
            'users' => $userRepository->findBy([]),
            'boughtToday' => $itemHistoryRepository->getCountOfBoughtItems(new DateTime()),
            'boughtAll' => $itemHistoryRepository->getCountOfBoughtItems(),
            'serverList' => array_slice($serverRepository->findAll(), 0,3)
        ]);
    }
}