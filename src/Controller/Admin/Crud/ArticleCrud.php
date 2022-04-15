<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
        $image =
            ImageField::new('image')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->setSortable(false)
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]);

        return [
            TextField::new('title')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('subtitle')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextareaField::new('text')
                ->setFormTypeOption('attr', ['class' => 'editor']),
            $pageName === Crud::PAGE_EDIT
                ? $image->setFormTypeOption('required', false)
                : $image,
        ];
    }
}