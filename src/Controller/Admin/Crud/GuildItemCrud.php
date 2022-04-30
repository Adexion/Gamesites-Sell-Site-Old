<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use App\Entity\GuildItem;
use App\Controller\Admin\Field\EntityField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class GuildItemCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GuildItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis")
            ->setEntityLabelInPlural("Guild Item");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            NumberField::new('count')
                ->setFormTypeOption('constraints', [new Length(['max' => 64])]),
            EntityField::new('image')
                ->setClass(Image::class, 'name')
                ->setChoiceValue('image')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->hideOnIndex()
        ];
    }
}