<?php

namespace App\Form\Payment\Operator;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MicroSMSType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shopid', HiddenType::class)
            ->add('signature', HiddenType::class)
            ->add('amount', HiddenType::class)
            ->add('control', HiddenType::class)
            ->add('return_urlc', HiddenType::class)
            ->add('return_url', HiddenType::class);
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
                'action' => 'https://microsms.pl/api/bankTransfer/',
                'method' => Request::METHOD_GET,
            ]);
    }
}