<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Field\ImageRepositoryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ArticleCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Article $entityInstance */
        $entityInstance->setAuthor($this->getUser());

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('subtitle')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            ImageRepositoryField::new('image')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
            TextareaField::new('text')
                ->setFormTypeOption('attr', ['class' => 'editor']),

        ];
    }
}