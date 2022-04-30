<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Head;
use App\Form\HeadFieldType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class HeadCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis")
            ->setEntityLabelInPlural('Heading');
    }

    public static function getEntityFqcn(): string
    {
        return Head::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getDoctrine()->getRepository(Head::class)->count([])) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield TextareaField::new('custom');
        yield CollectionField::new('meta')
            ->setEntryIsComplex(true)
            ->setEntryType(HeadFieldType::class);

    }
}