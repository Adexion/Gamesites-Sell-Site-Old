<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Administration;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class AdministrationCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Administration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username')
                ->setFormTypeOption('constraints', [new Length(['max' => 80])]),
            TextareaField::new('description'),
            IntegerField::new('priority')
                ->setFormTypeOption('attr', ['min' => 1]),
        ];
    }
}