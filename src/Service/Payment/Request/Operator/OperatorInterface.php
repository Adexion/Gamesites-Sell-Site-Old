<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Entity\Payment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

interface OperatorInterface
{
    public function __construct(FormFactoryInterface $formFactory, string $uri);

    public function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface;
}