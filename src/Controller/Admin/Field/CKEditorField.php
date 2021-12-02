<?php

namespace App\Controller\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

final class CKEditorField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setFormType(CKEditorType::class);
    }

    public static function create(string $name, string $pageName): FieldInterface
    {
        return $pageName !== Crud::PAGE_INDEX
            ? CKEditorField::new($name)
                ->hideOnIndex()
                ->addWebpackEncoreEntries('ea-app')
                ->setFormTypeOption('config', [])
            : TextareaField::new($name)->stripTags(true);
    }
}