<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CKEditorField;
use App\Entity\Additional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdditionalCrud extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Additional::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->getDoctrine()->getRepository(Additional::class)->count([])) {
            $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            CKEditorField::create('descriptionOne', $pageName),
            CKEditorField::create('descriptionTwo', $pageName),
            CKEditorField::create('descriptionThree', $pageName),
            CKEditorField::create('descriptionFour', $pageName),
            TextField::new('discord'),
            TextField::new('ts3'),
            TextField::new('facebook'),
            TextField::new('yt'),
            TextField::new('instagram'),
            TextField::new('tiktok'),
        ];
    }
}