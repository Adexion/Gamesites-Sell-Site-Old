<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Form\Payment\Operator\DirectBillingType;
use Symfony\Component\Form\FormInterface;

final class CashBillOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, string $secret, string $hash = null): FormInterface
    {
        $formData = [
            'service' => $hash,
            'amount' => $item->getTotalDiscountedPrice($count),
            'desc' => $item->getName(),
            'userdata' => $id,
        ];

        $formData['sign'] = md5(
            $formData['service'] . '|' . $formData['amount'] . '||' . $formData['desc'] . '||' . $formData['userdata'] . '||||||||||||' . $secret
        );

        $form = $this->formFactory->create(DirectBillingType::class);
        $form->submit($formData);

        return $form;
    }
}