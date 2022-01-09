<?php

namespace App\Controller\Client;

use App\Repository\GuildItemRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractRenderController
{
    /** @Route(path="/") */
    public function indexNoLocale(Request $request): Response
    {
        return $this->redirectToRoute('index', ['_locale' => $request->getLocale()]);
    }

    /**
     * @Route(requirements={"_locale": "en|pl"}, path="{_locale}/", name="index")
     *
     * @throws Exception
     */
    public function index(GuildItemRepository $guildItemRepository, UserRepository $userRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'guildItem' => $guildItemRepository->findBy([]),
            'users' => $userRepository->findBy([])
        ]);
    }
}