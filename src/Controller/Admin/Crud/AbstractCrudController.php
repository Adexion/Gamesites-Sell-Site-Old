<?php

namespace App\Controller\Admin\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as AbstractBaseCrudController;

abstract class AbstractCrudController extends AbstractBaseCrudController
{
    public abstract static function getEntityFqcn(): string;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('wpis')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}