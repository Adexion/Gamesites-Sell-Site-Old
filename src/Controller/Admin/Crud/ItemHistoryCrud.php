<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Enum\OperatorTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemHistoryCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItemHistory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('History')
            ->setEntityLabelInSingular('Wpis');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('status')
                ->setChoices(PaymentStatusEnum::toArray()),
            TextField::new('username')
                ->setDisabled(),
            EmailField::new('email')
                ->hideOnIndex()
                ->setDisabled(),
            MoneyField::new('price')
                ->setCurrency('PLN')
                ->setStoredAsCents(false)
                ->setDisabled(),
            NumberField::new('count')
                ->setDisabled(),
            MoneyField::new('totalPrice')
                ->setCurrency('PLN')
                ->setStoredAsCents(false)
                ->setDisabled(),
            AssociationField::new('item')
                ->setCrudController(ItemCrud::class)
                ->setDisabled(),
            ChoiceField::new('type')
                ->setChoices(OperatorTypeEnum::toArray() + ['VOUCHER' => 'voucher'])
                ->setDisabled(),
            DateTimeField::new('date')
                ->setDisabled(),
        ];
    }
}