<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Entity\Payment;
use Symfony\Component\Form\FormInterface;
use App\Form\Payment\Operator\MicroSMSType;

final class MicroSMSOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, Payment $payment): FormInterface
    {
        $formData = [
            'shopid' => $payment->getSecret(),
            'signature' => md5($payment->getSecret() . $payment->getHash() . $item->getTotalDiscountedPrice($count)),
            'amount' => $item->getTotalDiscountedPrice($count),
            'control' => $id,
            'return_urlc' => sprintf('%s/api/payment/status/6', $this->uri),
            'return_url' => sprintf('%s/payment', $this->uri),
            'description' => $data['username'] . ' - ' . $item->getName() . ' x' . $count,
            'test' => $payment->getIsTest() ? 'true' : 'false',
        ];

        $form = $this->formFactory->create(MicroSMSType::class, null, [
            'action' => $payment->getIsTest()
                ? 'http://test.microsms.pl/api/bankTransfer/'
                : 'https://microsms.pl/api/bankTransfer/',
        ]);
        $form->submit($formData);

        return $form;
    }
}