<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CKEditorField;
use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

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
        $image = ImageField::new('image')
            ->setUploadDir($this->getParameter('uploadPath'))
            ->setBasePath($this->getParameter('basePath'))
            ->setHelp('Caution! Deleting this entity will be required to change the image!')
            ->setFormTypeOption('constraints', [new Length(['max' => 255])]);

        return [
            $pageName === Crud::PAGE_EDIT
                ? $image->setFormTypeOption('required', false)
                : $image,
            TextField::new('name')
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextareaField::new('shortDescription'),
            CKEditorField::create('description', $pageName)
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