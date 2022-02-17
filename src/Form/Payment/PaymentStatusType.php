<?php

namespace App\Form\Payment;

use App\Enum\PaymentStatusEnum;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Optional;

class PaymentStatusType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('KWOTA', TextType::class)
            ->add('ID_PLATNOSCI', TextType::class)
            ->add('ID_ZAMOWIENIA', TextType::class)
            ->add('STATUS', ChoiceType::class, ['choices' => PaymentStatusEnum::toArray()])
            ->add('SEKRET', TextType::class)
            ->add('HASH', TextType::class, ['constraints' => new Optional()]);
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