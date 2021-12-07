<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Bans;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BansCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bans::class;
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
            TextField::new('columnOne', 'Date From'),
            TextField::new('columnTwo', 'Reason')
        ];
    }
}