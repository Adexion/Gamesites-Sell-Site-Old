<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Server;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SebastianBergmann\CodeCoverage\Report\Text;

class ServerCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('serverName'),
            TextareaField::new('description'),
            TextField::new('RConIp'),
            NumberField::new('RConPort'),
            TextField::new('RConPassword'),
            TextField::new('minecraftQueryIp'),
            NumberField::new('minecraftQueryPort'),
            BooleanField::new('isDefault')
        ];
    }
}