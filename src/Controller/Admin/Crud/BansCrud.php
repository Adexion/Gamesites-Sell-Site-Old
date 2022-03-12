<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Bans;
use App\Enum\DatabaseTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class BansCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getDoctrine()->getRepository(Bans::class)->count([])) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public static function getEntityFqcn(): string
    {
        return Bans::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('ip'),
            NumberField::new('port')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            ChoiceField::new('databaseType')
                ->setChoices(DatabaseTypeEnum::toArray()),
            TextField::new('login')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('password')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('database')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('directory')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setLabel('Kolumna z nickiem'),
            TextField::new('columnOne', 'Date')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setLabel('Kolumna z datą'),
            TextField::new('columnTwo', 'Reason')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
            ->setLabel('Kolumna z powodem'),

        ];
    }
}