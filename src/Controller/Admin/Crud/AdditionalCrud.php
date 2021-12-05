<?php

namespace App\Controller\Admin\Crud;

use App\Controller\Admin\Field\CKEditorField;
use App\Entity\Additional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
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
            TextareaField::new('siteTitle'),
            TextareaField::new('mainText'),
            TextareaField::new('mainDescription'),
            TextareaField::new('trailerText'),
            TextField::new('discord')
                ->hideOnIndex(),
            TextField::new('ts3')
                ->hideOnIndex(),
            TextField::new('facebook')
                ->hideOnIndex(),
            TextField::new('yt')
                ->hideOnIndex(),
            TextField::new('instagram')
                ->hideOnIndex(),
            TextField::new('tiktok')
                ->hideOnIndex(),
            TextField::new('trailer')
                ->hideOnIndex(),
        ];
    }
}