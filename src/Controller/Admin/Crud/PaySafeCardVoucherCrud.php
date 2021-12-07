<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\PaySafeCard;
use App\Entity\PaySafeCardVoucher;
use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaySafeCardVoucherCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PaySafeCardVoucher::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('hash')
                ->setDisabled(),
            EntityField::new('paySafeCard')
                ->setClass(PaySafeCard::class, 'code')
                ->setDisabled(),
            EntityField::new('paySafeCard')
                ->setClass(PaySafeCard::class, 'username'),
            EntityField::new('voucher')
                ->setClass(Voucher::class, 'code')
                ->setDisabled(),
        ];
    }
}