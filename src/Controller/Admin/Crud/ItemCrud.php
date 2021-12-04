<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CKEditorField;
use App\Controller\Admin\Field\EntityField;
use App\Entity\Item;
use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Type;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
            ImageField::new('image')
                ->setUploadDir($this->getParameter('uploadPath'))
                ->setBasePath($this->getParameter('basePath'))
                ->setHelp('Caution! Deleting this entity will be required to change the image!')
                ->hideWhenUpdating(),
            TextField::new('name'),
            CKEditorField::create('description', $pageName),
            MoneyField::new('price')
                ->setCurrency('PLN')
                ->setNumDecimals(2)
                ->setStoredAsCents(false)
                ->setFormTypeOption('html5', true),
            CollectionField::new('command')
                ->allowAdd(true)
                ->allowDelete(true),
            TextareaField::new('shortDescription'),
            EntityField::new('server')
                ->setClass(Server::class, 'serverName')
                ->setHelp('First create server')
        ];
    }
}