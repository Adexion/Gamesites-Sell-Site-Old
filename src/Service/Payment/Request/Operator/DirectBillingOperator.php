<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Form\Payment\Operator\DirectBillingType;
use Symfony\Component\Form\FormInterface;

final class DirectBillingOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, string $secret, string $hash): FormInterface
    {
        $formData = [
            'SEKRET' => $secret,
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