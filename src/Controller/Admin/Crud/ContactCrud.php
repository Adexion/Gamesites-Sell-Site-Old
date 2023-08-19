<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class ContactCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextareaField::new('description')
                ->setDisabled()
                ->hideOnIndex(),
            TextEditorField::new('description')
                ->onlyOnIndex(),
            TextField::new('name')
                ->setDisabled()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('email')
                ->setDisabled()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
        ];
    }
}