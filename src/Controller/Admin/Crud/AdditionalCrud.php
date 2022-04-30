<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Additional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;

class AdditionalCrud extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Wpis");
    }

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
            TextField::new('siteName'),
            TextareaField::new('mainText'),
            TextareaField::new('mainDescription'),
            TextareaField::new('trailerText'),
            TextareaField::new('guildText'),
            TextField::new('discord')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('ts3')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('facebook')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('yt')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('instagram')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('tiktok')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
            TextField::new('trailer')->hideOnIndex()
                ->setFormTypeOption('constraints', [new Length(['max' => 255])]),
        ];
    }
}