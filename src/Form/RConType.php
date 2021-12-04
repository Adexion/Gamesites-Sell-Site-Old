<?php

namespace App\Form;

use App\Entity\Server;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RConType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('server', EntityType::class, [
                'class' => Server::class,
                'choice_label' => 'serverName'
            ])
            ->add('command', TextType::class);
    }
}