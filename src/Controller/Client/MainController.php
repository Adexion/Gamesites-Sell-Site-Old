<?php

namespace App\Controller\Client;

use App\Repository\GuildItemRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
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
    public function index(GuildItemRepository $repository): Response
    {
        return $this->render('client/index.html.twig', [
            'guildItem' => $repository->findBy([]),
        ]);
    }
}