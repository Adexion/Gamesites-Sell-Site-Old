<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Server;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Validator\Constraints\Length;

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
            TextField::new('serverName')
                ->setFormTypeOption('constraints', [new Length(['max' => 60])]),
            TextareaField::new('description'),
            TextField::new('RConIp')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            NumberField::new('RConPort')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('RConPassword')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('minecraftQueryIp')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            NumberField::new('minecraftQueryPort')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            BooleanField::new('isDefault')
        ];
    }
}