<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Email;

class UserCrud extends AbstractCrudController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $encodedPassword = $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword());
        $entityInstance->setPassword($encodedPassword);

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $encodedPassword = $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword());
        $entityInstance->setPassword($encodedPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email')
                ->setFormTypeOption('constraints', [
                    new Email([
                        'mode' => Email::VALIDATION_MODE_STRICT
                    ])
                ]),
            TextField::new('username'),
            TextareaField::new('description'),
            ArrayField::new('roles')
                ->setDisabled(true)
                ->setValue(['ROLE_ADMIN']),
            TextField::new('password')
                ->setFormType(PasswordType::class),
        ];
    }
}