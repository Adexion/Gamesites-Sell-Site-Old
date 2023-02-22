<?php

namespace App\Controller\Client;

use App\Repository\ArticleRepository;
use DateTime;
use App\Enum\RankEnum;
use Twig\Error\SyntaxError;
use App\Form\DevTemplateType;
use App\Repository\LinkRepository;
use App\Repository\RankRepository;
use App\Repository\RuleRepository;
use App\Repository\ServerRepository;
use App\Service\GuildItemListBuilder;
use App\Repository\GuildItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ItemHistoryRepository;
use App\Repository\ConfigurationRepository;
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
        return $this->renderTheme('index.html.twig', [
            'guildItem' => (new GuildItemListBuilder)->buildList($guildItemRepository->findAll()),
            'administration' => $administrationRepository->findBy([], ['priority' => 'ASC']),
            'boughtToday' => $itemHistoryRepository->getCountOfBoughtItems(new DateTime()),
            'boughtAll' => $itemHistoryRepository->getCountOfBoughtItems(),
            'serverList' => array_slice($serverRepository->findAll(), 0, 3),
            'guildRank' => $rankRepository->findRemote(['type' => RankEnum::GUILD]),
            'playerRank' => $rankRepository->findRemote(['type' => RankEnum::PLAYER], $request->query->all())
        ]);
    }

    /**
     * @Route(path="/template", name="app_template")
     */
    public function template(Request $request, ConfigurationRepository $configurationRepository, EntityManagerInterface $em): Response
    {
        if (!isset($_ENV['APP_DEV']) || $_ENV['APP_DEV'] !== 'development') {
            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(DevTemplateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $configuration = $configurationRepository->findOneBy([]);
            $configuration->setTemplate($form->getData()['template']);

            $em->persist($configuration);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route(path="/{name}", name="app_own_redirect")
     * @throws SyntaxError
     */
    public function ownRedirect(string $name, LinkRepository $linkRepository, RuleRepository $repository): Response
    {
        $link = $linkRepository->findOneBy(['name' => $name]);

        if (!$link && ($name === 'rule' || $name === 'regulamin')) {
            return $this->rule($repository);
        }
        if (!$link) {
            return $this->redirectToRoute('index');
        }

        return $this->redirect($link->getUri());
    }

    /**
     * @Route ({"en": "/rule", "pl": "/regulamin"}, name="rule")
     * @throws SyntaxError
     */
    public function rule(RuleRepository $repository): Response
    {
        $rule = $repository->findOneBy([]);

        return $this->renderTheme('rule.html.twig', [
            'rule' => $rule ? $rule->getHtml() : '',
        ]);
    }
}