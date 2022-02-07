<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Payment;
use App\Enum\PaymentTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PaymentCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('type')
                ->setChoices(PaymentTypeEnum::toArray()),
            TextField::new('secret'),
            TextField::new('hash'),
            BooleanField::new('isActive')
        ];
    }
}