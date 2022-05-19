<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Form\Payment\Operator\HotPayType;
use Symfony\Component\Form\FormInterface;
use App\Form\Payment\Operator\PayByLinkPscType;

final class PayByLinkPscOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, string $secret, string $hash): FormInterface
    {
        $formData = [
            'userid' => $secret,
            'shopid' => $hash,
            'amount' => $item->getTotalDiscountedPrice($count),
            'return_ok' => sprintf('%s/payment', $this->uri),
            'return_fail' => sprintf('%s/payment', $this->uri),
            'url' => sprintf('%s/api/payment/status/7', $this->uri),
            'control' => $id,
            'hash' => hash('sha256', $secret . $hash . $item->getTotalDiscountedPrice($count)),
            'description' => $item->getName(),
        ];

        $form = $this->formFactory->create(PayByLinkPscType::class);
        $form->submit($formData);

        return $form;
    }
}