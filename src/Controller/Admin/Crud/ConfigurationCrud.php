<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use App\Entity\Configuration;
use App\Enum\TemplateEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\Field\ImageRepositoryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

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
            TextField::new('ip')
                ->setLabel('IP do kopiowania dla użytkowników')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            MoneyField::new('target')
                ->setCurrency('PLN')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setFormTypeOption('constraints', [new Range(['max' => 999.99, 'min' => 0.01])]),
            ChoiceField::new('template')
                ->setChoices(TemplateEnum::toArray()),
            BooleanField::new('simplePaySafeCard'),
            TextField::new('simplePayPal'),
            BooleanField::new('showBigLogo'),
            ImageRepositoryField::new('logo')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
            ImageRepositoryField::new('background')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
        ];
    }
}