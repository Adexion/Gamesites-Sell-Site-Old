<?php

namespace App\Controller\Admin\Crud;

use App\Entity\GuildItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
            ChoiceField::new('image')
                ->setChoices(fn() => $this->getImagesList())
        ];
    }
}