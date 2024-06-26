<?php

namespace App\Service\Payment\Request;

use App\Entity\Item;
use RuntimeException;
use App\Entity\Payment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OperatorFactory
{
    private FormFactoryInterface $formFactory;
    private string $uri;

    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->uri = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    }

    function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface
    {
        $className = 'App\Service\Payment\Request\Operator\\' . $data['payment'] . 'Operator';

        if (!class_exists($className)) {
            throw new RuntimeException();
        }

        return (new $className($this->formFactory, $this->uri))->getForm($data, $item, $id, $count, $payment);
    }
}