<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Entity\Payment;
use App\Form\Payment\Operator\DirectBillingType;
use Symfony\Component\Form\FormInterface;

final class DirectBillingOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface
    {
        $formData = [
            'SEKRET' => $payment->getSecret(),
            'KWOTA' => $item->getTotalDiscountedPrice($count),
            'PRZEKIEROWANIE_SUKCESS' => sprintf('%s/payment', $this->uri),
            'PRZEKIEROWANIE_BLAD' => sprintf('%s/payment', $this->uri),
            'ID_ZAMOWIENIA' => $id,
        ];

        $form = $this->formFactory->create(DirectBillingType::class);
        $form->submit($formData);

        return $form;
    }
}