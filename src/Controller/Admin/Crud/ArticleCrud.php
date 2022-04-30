<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\Field\EntityField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

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
            TextareaField::new('text')
                ->setFormTypeOption('attr', ['class' => 'editor']),
            EntityField::new('image')
                ->setClass(Image::class, 'name')
                ->setChoiceValue('image')
        ];
    }
}