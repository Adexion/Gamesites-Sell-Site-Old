<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\PaySafeCard;
use App\Entity\PaySafeCardVoucher;
use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaySafeCardVoucherCrud extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return PaySafeCardVoucher::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setHelp(Crud::PAGE_INDEX,
                $this->translator->trans('Allow removing psc with voucher')
            );
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
            TextField::new('hash'),
            EntityField::new('paySafeCard', 'Username')
                ->setClass(PaySafeCard::class, 'username'),
            EntityField::new('paySafeCard', 'Email')
                ->setClass(PaySafeCard::class, 'email'),
            EntityField::new('paySafeCard')
                ->setClass(PaySafeCard::class, 'code'),
            EntityField::new('voucher')
                ->setClass(Voucher::class, 'code'),
        ];
    }
}