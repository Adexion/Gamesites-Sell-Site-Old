<?php

namespace App\Service\Payment\Request\Operator;

use Symfony\Component\Form\FormFactoryInterface;

abstract class AbstractOperator
{
    protected FormFactoryInterface $formFactory;
    protected string $uri;

    public function __construct(FormFactoryInterface $formFactory, string $uri)
    {
        $this->formFactory = $formFactory;
        $this->uri = $uri;
    }

}