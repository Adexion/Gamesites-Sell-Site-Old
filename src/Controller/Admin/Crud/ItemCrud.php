<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Item;
use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Symfony\Component\Validator\Constraints\NotBlank;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ItemCrud extends AbstractCrudController
{
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
            ChoiceField::new('image')
                ->setChoices(fn() => $this->getImagesList()),
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
                ->setHelp("%player% - nick gracza, %amount% - ilość użyć przedmiotu")
                ->setFormTypeOption('constraints', [new All([new Length(['max' => 255])])]),
            BooleanField::new('multiple'),
            BooleanField::new('visible'),
            AssociationField::new('server')
                ->setCrudController(ServerCrud::class)
                ->autocomplete()
        ];
    }
}