<?php

namespace App\Controller\Client;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractRenderController
{
    private const PLAYER_AVATAR = 'https://cravatar.eu/avatar/';

    /**
     * @Route(path="/article/show/{slug}", name="article")
     */
    public function article(ArticleRepository $repository, int $slug): Response
    {
        /** @var Article $article */
        $article = $repository->find($slug);

        return $this->renderTheme('article.html.twig', [
            'article' => $article,
            'avatar' => self::PLAYER_AVATAR . $article->getAuthor()->getUsername(),
        ]);
    }

    /**
     * @Route(path="/article/list/{slug}", name="article-list")
     */
    public function articleList(ArticleRepository $repository, int $slug = 1): Response
    {
        /** @var Article[] articleList */
        $articleList = $repository->getArticles($slug);

        return $this->renderTheme('articleList.html.twig', [
            'articleList' => $articleList,
            'count' => $repository->count([]),
            'perPages' => ArticleRepository::ARTICLE_PER_PAGES,
            'currentPage' => $slug,
        ]);
    }
}