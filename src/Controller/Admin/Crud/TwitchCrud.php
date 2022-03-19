<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Server;
use App\Entity\TwitchStreamer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\NotBlank;

class TwitchCrud extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Twitch");
    }

    public static function getEntityFqcn(): string
    {
        return TwitchStreamer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('channelName'),
            TextField::new('minecraftName'),
            EntityField::new('server')
                ->setRequired(true)
                ->setClass(Server::class, 'serverName')
                ->setHelp('Informacja na którym z serwerów ma być streamer')
                ->setFormTypeOption('constraints', [new NotBlank()]),
        ];
    }
}