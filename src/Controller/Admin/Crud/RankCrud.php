<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Rank;
use App\Enum\RankEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class RankCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Rank::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('ip'),
            NumberField::new('port')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('login')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('password')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('database')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('directory')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('columnOne', 'Date')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('columnTwo', 'Reason'),
            ChoiceField::new('type')
                ->setChoices(RankEnum::toArray()),
        ];
    }
}