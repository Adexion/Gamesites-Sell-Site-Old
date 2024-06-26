<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController as AbstractBaseCrudController;

abstract class AbstractCrudController extends AbstractBaseCrudController
{
    public abstract static function getEntityFqcn(): string;

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Wpis');
    }
}