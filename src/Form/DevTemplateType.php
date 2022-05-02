<?php

namespace App\Form;

use App\Enum\TemplateEnum;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DevTemplateType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('template', ChoiceType::class, [
                'choices' => TemplateEnum::toArray(),
                'attr' => [
                    'class' => 'select-template'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-template btn btn-color-g'
                ]
            ]);
    }
}