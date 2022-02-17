<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Form\Payment\Operator\PaySefeCardType;
use Symfony\Component\Form\FormInterface;

final class PaySafeCardOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, string $secret, int $id): FormInterface
    {
        $formData = [
            'SEKRET' => $secret,
            'KWOTA' => $item->getDiscountedPrice(),
            'NAZWA_USLUGI' => $item->getName(),
            'ADRES_WWW' => sprintf('%s/payment', $this->uri),
            'ID_ZAMOWIENIA' => $id,
            'EMAIL' => $data['email']
        ];

        $form = $this->formFactory->create(PaySefeCardType::class);
        $form->submit($formData);

        return $form;
    }
}