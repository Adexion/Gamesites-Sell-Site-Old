<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ItemType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, [
                'constraints' => [
                    new Length(['max' => 15])
                ]
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => 'type',
                'choice_translation_domain' => 'messages',
            ])
            ->add('uri', HiddenType::class)
            ->add('locale', HiddenType::class);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}