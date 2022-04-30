<?php

namespace App\Form;

use App\Entity\Meta;
use App\Enum\MetaTypeEnum;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HeadFieldType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => MetaTypeEnum::toArray()
            ])
            ->add('value', TextType::class)
            ->add('content', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('data_class', Meta::class);
    }
}