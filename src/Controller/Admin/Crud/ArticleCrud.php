<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CKEditorField;
use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $image =
            ImageField::new('image')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->setSortable(false);

        return [
            TextField::new('title'),
            TextField::new('subtitle'),
            CKEditorField::create('text', $pageName),
            $pageName === Crud::PAGE_EDIT
                ? $image->setFormTypeOption('required', false)
                : $image,
        ];
    }
}