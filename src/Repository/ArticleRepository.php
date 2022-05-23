<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public const ARTICLE_PER_PAGES = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getLastArticles(): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select(
                'article.id, article.image, article.subhead, article.title, article.text, article.shortText, article.createdAt, user.nick as author'
            )
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->orderBy('article.id', "DESC")
            ->setMaxResults(4);

        return $builder->getQuery()->execute();
    }

    public function getArticles(int $page): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $page = max($page, 1);

        $builder
            ->select('article.id, article.image, article.subtitle, article.title, article.text, user.nick as author')
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->orderBy('article.id', "DESC")
            ->setMaxResults(self::ARTICLE_PER_PAGES)
            ->setFirstResult(self::ARTICLE_PER_PAGES * ($page - 1));

        return $builder->getQuery()->execute();
    }
}
