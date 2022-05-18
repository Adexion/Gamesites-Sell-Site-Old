<?php

namespace App\Service\Payment\Request\Operator;

use App\Entity\Item;
use App\Form\Payment\Operator\TPayType;
use App\Form\Payment\Operator\DirectBillingType;
use Symfony\Component\Form\FormInterface;

final class TPayOperator extends AbstractOperator implements OperatorInterface
{
    public function getForm(array $data, Item $item, int $id, int $count, string $secret, string $hash = null): FormInterface
    {
        $formData = [
            'id' => $id,
            'amount' => $item->getTotalDiscountedPrice($count),
            'description' => $item->getName(),
            'crc' => $id,
            'return_url' => sprintf('%s/payment/', $this->uri),
        ];

        $formData['md5sum'] = md5(implode('&', [$id, $item->getTotalDiscountedPrice($count), $item->getName() . ' ' . $id, $hash]));

        $form = $this->formFactory->create(TPayType::class);
        $form->submit($formData);

        return $form;
    }
}