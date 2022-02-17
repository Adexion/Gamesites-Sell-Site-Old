<?php

namespace App\Form\Payment\Operator;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Optional;

class DirectBillingType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SEKRET', HiddenType::class)
            ->add('KWOTA', HiddenType::class)
            ->add('PRZEKIEROWANIE_SUKCESS', HiddenType::class)
            ->add('PRZEKIEROWANIE_BLAD', HiddenType::class)
            ->add('ID_ZAMOWIENIA', HiddenType::class);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'csrf_protection' => false,
                'action' => 'https://directbilling.hotpay.pl',
                'method' => Request::METHOD_POST,
            ]);
    }
}