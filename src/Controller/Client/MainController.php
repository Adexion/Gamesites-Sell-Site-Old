<?php

namespace App\Controller\Client;

use App\Repository\GuildItemRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractRenderController
{
    /** @Route(path="/", name="index") */
    public function index(GuildItemRepository $guildItemRepository, UserRepository $userRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'guildItem' => $guildItemRepository->findBy([]),
            'users' => $userRepository->findBy([])
        ]);
    }
}