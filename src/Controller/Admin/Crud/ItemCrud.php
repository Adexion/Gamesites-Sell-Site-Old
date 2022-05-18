<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Item;
use App\Entity\Image;
use App\Entity\Server;
use App\Enum\ItemTypeEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Field\ImageRepositoryField;
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
            ImageRepositoryField::new('image')
                ->setImageRepository($this->getDoctrine()->getRepository(Image::class)),
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            ChoiceField::new('type')
                ->setChoices(ItemTypeEnum::toArray())
                ->hideOnIndex(),
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
            BooleanField::new('isMainItem')
                ->setHelp('Jeżeli zaznaczone, przedmiot ten będzie jedynym dostępnym w sklepie. Automatycznie zostaniesz na niego przekierowany'),
            AssociationField::new('server')
                ->setCrudController(ServerCrud::class)
                ->autocomplete()
        ];
    }
}