<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Optional;

use function Sodium\add;

class PaymentType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ID_ZAMOWIENIA', HiddenType::class)
            ->add('NAZWA_USLUGI', HiddenType::class, ['constraints' => new Optional()])
            ->add('SEKRET', HiddenType::class)

            ->add('KWOTA', HiddenType::class)
            ->add('EMAIL', HiddenType::class)

            ->add('ADRES_WWW', HiddenType::class, ['constraints' => new Optional()])
            ->add('PRZEKIEROWANIE_SUKCESS', HiddenType::class, ['constraints' => new Optional()])
            ->add('PRZEKIEROWANIE_BLAD', HiddenType::class, ['constraints' => new Optional()]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('csrf_protection', false);
    }
}