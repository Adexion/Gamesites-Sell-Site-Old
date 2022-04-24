<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Configuration;
use App\Enum\TemplateEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class ConfigurationCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

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
        $logo =
            ImageField::new('logo')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->setSortable(false)
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->hideOnIndex();

        return [
            TextField::new('ip')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('serverName')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextareaField::new('description')
                ->hideOnIndex(),
            MoneyField::new('target')
                ->setCurrency('PLN')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setFormTypeOption('constraints', [new Range(['max' => 999.99, 'min' => 0.01])]),
            ChoiceField::new('template')
                ->setChoices(TemplateEnum::toArray()),
            BooleanField::new('simplePaySafeCard'),
            BooleanField::new('showBigLogo'),
            $pageName === Crud::PAGE_EDIT
                ? $logo->setFormTypeOption('required', false)
                : $logo,
            ImageField::new('background')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->setSortable(false)
                ->setHelp('Caution! Deleting this entity will be required to change the image!')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->hideOnIndex(),
        ];
    }
}