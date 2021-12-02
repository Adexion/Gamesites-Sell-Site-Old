<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VoucherCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Voucher::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code')
                ->setFormTypeOption('attr', ['value' => uniqid('MG', true)]),
            NumberField::new('times')
                ->setFormTypeOption('attr', ['value' => 1])
                ->setNumDecimals(0)
                ->setHelp('-1 unlimited, 0 is of, 1+ is specific use number'),
            EntityField::new('item')
                ->setClass(Item::class, 'name'),
            DateField::new('date', 'Expired')
                ->renderAsNativeWidget(true)
                ->setHelp('Max working time')
        ];
    }
}