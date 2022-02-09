<?php

namespace App\Service\Payment;

use App\Entity\Item;
use App\Form\PaymentType;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class PaymentResponseBuilder
{
    private PaymentRequestBuilder $requestBuilder;
    private FormFactoryInterface $formFactory;

    public function __construct(PaymentRequestBuilder $requestBuilder, FormFactoryInterface $formFactory)
    {
        $this->requestBuilder = $requestBuilder;
        $this->formFactory = $formFactory;
    }

    /** @throws OptimisticLockException|ORMException */
    public function getResponse(FormInterface $form, Item $item, callable $callable): Response
    {
        $payment = $this->requestBuilder->getRequest($form->getData(), $item);

        $redirectForm = $this->formFactory->create(PaymentType::class);
        $redirectForm->submit($payment);

        $response = $callable([
            'form' => $redirectForm->createView(),
            'paymentType' => $form->getData()['payment'],
        ]);

        $cookie = new Cookie('paymentId', $payment['ID_ZAMOWIENIA'], strtotime('now + 30 minutes'));
        $response->headers->setCookie($cookie);

        return $response;
    }
}