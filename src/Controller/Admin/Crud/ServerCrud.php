<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Server;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServerCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('serverName'),
            TextField::new('RConIp'),
            NumberField::new('RConPort'),
            TextField::new('RConPassword'),
        ];
    }
}