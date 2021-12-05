<?php

namespace App\Controller\Admin\Crud;

use App\Entity\GuildItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GuildItemCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GuildItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Guild Item")
            ->setEntityLabelInPlural("Guild Item");
    }

    public function configureFields(string $pageName): iterable
    {
        $image = ImageField::new('image')
            ->setUploadDir($this->getParameter('uploadPath'))
            ->setBasePath($this->getParameter('basePath'));

        return [
            TextField::new('name'),
            NumberField::new('count'),
            $pageName === Crud::PAGE_EDIT
                ? $image->setFormTypeOption('required', false)
                : $image,
        ];
    }
}