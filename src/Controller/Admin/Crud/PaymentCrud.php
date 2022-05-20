<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Payment;
use App\Enum\OperatorTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class PaymentCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('type')
                ->setChoices(OperatorTypeEnum::toArray()),
            TextField::new('secret')
                ->setHelp('Pole w zależności od systemu płatności powinno zawierać: CashBill - secret, HotPay - SEKRET, MicroSMS - shop id, PayByLink PSC - user id')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('hash')
                ->setHelp('Pole w zależności od systemu płatności powinno zawierać: CashBill - service, MicroSMS - hash, PayByLink PSC - shop id, TPay - Merchant Security Code')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('other')
                ->setHelp('Pole w zależności od systemu płatności powinno zawierać: PayByLink PSC - pin')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            BooleanField::new('isActive'),
        ];
    }
}