<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Item;
use App\Entity\Image;
use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\Field\EntityField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\Validator\Constraints\NotBlank;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ItemCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Item $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setPrice(round($entityInstance->getPrice(), 2));

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EntityField::new('image')
                ->setClass(Image::class, 'name')
                ->setChoiceValue('image')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])])
                ->hideOnIndex(),
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextareaField::new('shortDescription'),
            TextareaField::new('description')
                ->setFormTypeOption('attr', ['class' => 'editor'])
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            MoneyField::new('price')
                ->setCurrency('PLN')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setFormTypeOption('constraints', [new Range(['max' => 999.99, 'min' => 0.01])]),
            PercentField::new('discount')
                ->setNumDecimals(0)
                ->setFormTypeOption('constraints', [new Range(['max' => 100, 'min' => 0])]),
            CollectionField::new('command')
                ->allowAdd(true)
                ->allowDelete(true)
                ->setHelp("%player% - nick gracza, %amount% - ilość użyć przedmiotu")
                ->setFormTypeOption('constraints', [new All([new Length(['max' => 255])])]),
            BooleanField::new('multiple'),
            BooleanField::new('visible'),
            EntityField::new('server')
                ->setRequired(true)
                ->setClass(Server::class, 'serverName')
                ->setHelp('First create server')
                ->setFormTypeOption('constraints', [new NotBlank()]),
        ];
    }
}