<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Entity\Payment;
use App\Form\Payment\Operator\HotPayType;
use Symfony\Component\Form\FormInterface;

final class HotPayOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface
    {
        $formData = [
            'SEKRET' => $payment->getSecret(),
            'KWOTA' => $item->getTotalDiscountedPrice($count),
            'NAZWA_USLUGI' => $item->getName(),
            'ADRES_WWW' => sprintf('%s/payment', $this->uri),
            'ID_ZAMOWIENIA' => $id,
            'EMAIL' => $data['email']
        ];

        $form = $this->formFactory->create(HotPayType::class);
        $form->submit($formData);

        return $form;
    }
}