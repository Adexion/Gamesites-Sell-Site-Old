<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Item;
use App\Entity\Voucher;
use App\Controller\Admin\Field\EntityField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VoucherCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Voucher::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code')
                ->setFormTypeOption('attr', ['value' => uniqid('MG', true)])
                ->setFormTypeOption('constraints', [new Length(['max' => 25])]),
            NumberField::new('times')
                ->setFormTypeOption('attr', ['value' => 1])
                ->setNumDecimals(0)
                ->setHelp('-1 unlimited, 0 is off, 1+ is specific use number')
                ->setFormTypeOption('constraints', [new Range(['max' => 100])]),
            AssociationField::new('item')
                ->setCrudController(Item::class),
            DateField::new('date', 'Expired')
                ->renderAsNativeWidget(true),
        ];
    }
}