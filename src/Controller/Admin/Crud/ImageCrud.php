<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis")
            ->setEntityLabelInPlural('Twoje obrazy');
    }

    public static function getEntityFqcn(): string
    {
        return Image::class;
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
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            $pageName === Crud::PAGE_EDIT
                ? $image->setFormTypeOption('required', false)
                : $image,
        ];
    }
}