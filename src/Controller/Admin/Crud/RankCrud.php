<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Rank;
use App\Enum\RankEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RankCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Rank::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('ip'),
            NumberField::new('port'),
            TextField::new('login'),
            TextField::new('password'),
            TextField::new('database'),
            TextField::new('directory'),
            TextField::new('name'),
            TextField::new('columnOne', 'Punkty'),
            ChoiceField::new('type')
                ->setChoices(RankEnum::toArray()),
        ];
    }
}