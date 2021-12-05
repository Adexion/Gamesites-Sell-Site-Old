<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Configuration;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Email;

class ConfigurationCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Configuration::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getDoctrine()->getRepository(Configuration::class)->count([])) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email')
            ->setFormTypeOption('constraints', [
                new Email(['mode' => Email::VALIDATION_MODE_STRICT])
            ]),
            TextField::new('serverName'),
            TextField::new('ip'),
            ImageField::new('logo')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->hideWhenUpdating(),
            TextField::new('minecraftQueryIp'),
            NumberField::new('minecraftQueryPort'),
        ];
    }
}