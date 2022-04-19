<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Link;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LinkCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Link::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Przekierowania')
            ->setEntityLabelInSingular('wpis');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            UrlField::new('uri')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
        ];
    }
}