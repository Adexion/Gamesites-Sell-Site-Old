<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\PaySafeCard;
use App\Enum\PaySafeCardStatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaySafeCardCrud extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return PaySafeCard::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Wpis')
            ->setHelp(
                Crud::PAGE_INDEX,
                $this->translator->trans(
                    'PaySafeCard status WORKING when active it is allow to generating voucher for user'
                )
            );
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            CollectionField::new('code')
                ->allowDelete(false)
                ->allowAdd(false)
                ->setDisabled(),
            TextField::new('username')
                ->setDisabled(),
            AssociationField::new('item')
                ->setCrudController(ItemCrud::class)
                ->setDisabled(),
            DateField::new('date', 'Date From')
                ->setDisabled(),
            ChoiceField::new('status')
                ->setChoices(PaySafeCardStatusEnum::toArray()),
        ];
    }
}