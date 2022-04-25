<?php

namespace App\Controller\Client;

use DateTime;
use App\Enum\RankEnum;
use Twig\Error\SyntaxError;
use App\Repository\LinkRepository;
use App\Repository\RankRepository;
use App\Repository\RuleRepository;
use App\Repository\ServerRepository;
use App\Service\GuildItemListBuilder;
use App\Repository\GuildItemRepository;
use App\Repository\ItemHistoryRepository;
use App\Repository\AdministrationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            'guildItem' => (new GuildItemListBuilder)->buildList($guildItemRepository->findAll()),
            'administration' => $administrationRepository->findBy([], ['priority' => 'ASC']),
            'boughtToday' => $itemHistoryRepository->getCountOfBoughtItems(new DateTime()),
            'boughtAll' => $itemHistoryRepository->getCountOfBoughtItems(),
            'serverList' => array_slice($serverRepository->findAll(), 0, 3),
            'guildRank' => $rankRepository->findRemote(['type' => RankEnum::GUILD]),
            'playerRank' => $rankRepository->findRemote(['type' => RankEnum::PLAYER], $request->query->all()),
        ]);
    }

    /** @Route(path="/rule", name="rule") */
    public function rule(RuleRepository $repository): Response
    {
        $rule = $repository->findOneBy([]);

        return $this->render('client/rule.html.twig', [
            'rule' => $rule ? $rule->getHtml() : '',
        ]);
    }

    /**
     * @Route(path="/{name}", name="app_own_redirect")
     */
    public function ownRedirect(string $name, LinkRepository $linkRepository): RedirectResponse
    {
        $link = $linkRepository->findOneBy(['name' => $name]);
        if (!$link) {
            return $this->redirectToRoute('index');
        }

        return $this->redirect($link->getUri());
    }
}