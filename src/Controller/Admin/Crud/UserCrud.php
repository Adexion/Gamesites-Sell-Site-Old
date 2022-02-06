<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("wpis");
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

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $encodedPassword = $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword());
        $entityInstance->setPassword($encodedPassword);
        $entityInstance->setRoles(['ROLE_ADMIN']);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email')
                ->setFormTypeOption('constraints', [
                    new Email([
                        'mode' => Email::VALIDATION_MODE_STRICT,
                    ]),
                ]),
            TextField::new('password')
                ->setFormType(PasswordType::class),
        ];
    }
}