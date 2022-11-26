<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\ImageRepositoryField;
use App\Entity\Configuration;
use App\Entity\Image;
use App\Entity\Template;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class ConfigurationCrud extends AbstractCrudController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public static function getEntityFqcn(): string
    {
        return Configuration::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getDoctrine()->getRepository(Configuration::class)->count([])) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        $qb = $this->managerRegistry->getRepository(Template::class, 'configuration')->createQueryBuilder('template');

        return [
            TextField::new('ip')
                ->setLabel('IP do kopiowania dla użytkowników')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            MoneyField::new('target')
                ->setCurrency('PLN')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setFormTypeOption('constraints', [new Range(['max' => 999.99, 'min' => 0.01])]),
            AssociationField::new('template')
                ->setQueryBuilder(fn() => $qb),
            BooleanField::new('simplePaySafeCard'),
            TextField::new('simplePayPal'),
            BooleanField::new('showBigLogo'),
            ImageRepositoryField::new('logo')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
            ImageRepositoryField::new('background')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
        ];
    }
}