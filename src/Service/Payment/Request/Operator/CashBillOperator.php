<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Entity\Payment;
use App\Form\Payment\Operator\CashBillType;
use App\Form\Payment\Operator\DirectBillingType;
use Symfony\Component\Form\FormInterface;

final class CashBillOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface
    {
        $formData = [
            'service' => $payment->getHash(),
            'amount' => $item->getTotalDiscountedPrice($count),
            'desc' => $item->getName(),
            'userdata' => $id,
        ];

        $formData['sign'] = md5(
            $formData['service'] . '|' . $formData['amount'] . '||' . $formData['desc'] . '||' . $formData['userdata'] . '||||||||||||' . $payment->getSecret()
        );

        $form = $this->formFactory->create(CashBillType::class);
        $form->submit($formData);

        return $form;
    }
}