<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Rank;
use App\Enum\DatabaseTypeEnum;
use App\Enum\RankEnum;
use App\Form\AdditionalFieldType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class RankCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
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
            ChoiceField::new('type')
                ->setChoices(RankEnum::toArray()),
            ChoiceField::new('databaseType')
                ->setChoices(DatabaseTypeEnum::toArray()),
            TextField::new('login')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('password')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('database')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('displayName')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('directory')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setLabel('Kolumna z nickiem'),
            TextField::new('columnOne', 'Point')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->setLabel('Kolumna z punktami'),
            CollectionField::new('additionalFields')
                ->setEntryIsComplex(true)
                ->setEntryType(AdditionalFieldType::class)
        ];
    }
}