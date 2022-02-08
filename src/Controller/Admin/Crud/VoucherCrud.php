<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class VoucherCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
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
            EntityField::new('item')
                ->setClass(Item::class, 'name'),
            DateField::new('date', 'Expired')
                ->renderAsNativeWidget(true),
        ];
    }
}