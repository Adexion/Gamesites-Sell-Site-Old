<?php

namespace App\Form\Payment\Operator;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Optional;

class CashBillType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', HiddenType::class)
            ->add('amount', HiddenType::class)
            ->add('desc', HiddenType::class)
            ->add('userdata', HiddenType::class)
            ->add('sign', HiddenType::class);
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
                'action' => 'https://www.cashbill.pl/cblite/pay.php',
                'method' => Request::METHOD_POST,
            ]);
    }
}