<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Server;
use App\Enum\ConnectionEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class ServerCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('serverName')
                ->setRequired(false)
                ->setFormTypeOption('constraints', [new Length(['max' => 60])]),
            TextareaField::new('description'),
            ChoiceField::new('connectionType')
                ->setChoices(ConnectionEnum::toArray()),
            TextField::new('conIp', 'IP Połączenia')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setHelp('dotyczy rCon, Redisa i Pluginu')
                ->hideOnIndex(),
            TextField::new('conPassword', 'Hasło Połączenia')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setHelp('dotyczy rCon, Redisa i Pluginu')
                ->hideOnIndex(),
            NumberField::new('conPort', 'Port Połączenia')
                ->setFormTypeOption('constraints', [new Length(['max' => 5])])
                ->setHelp('dotyczy rCon, Redisa i Pluginu')
                ->hideOnIndex(),
            TextField::new('minecraftQueryIp')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->hideOnIndex(),
            NumberField::new('minecraftQueryPort')
                ->setFormTypeOption('constraints', [new Length(['max' => 5])])
                ->hideOnIndex(),
            BooleanField::new('showOnMainPage'),
            BooleanField::new('isDefault')
                ->setValue(false)
                ->setHelp('Ustaw jako włączony tylko dla głównego serwera. W innym przypadku aplikacja może nie działać prawidłowo.'),
        ];
    }
}