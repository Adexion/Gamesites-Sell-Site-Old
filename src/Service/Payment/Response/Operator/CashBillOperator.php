<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

class CashBillOperator extends OperatorAbstract implements OperatorInterface
{
    protected const FAILURE_STATUSES = ["ERR", "err"];

    /** @throws ORMException|OptimisticLockException */
    public function getResponse(array $request)
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['userdata']]);
        $paymentHash = $this->historyRepository->getPaymentHash($history);
        $paymentStatus = $request['status'];

        $this->handlePaymentExist($request, $history, $paymentHash);

        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        $this->execute($history);

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);
    }

    private function handlePaymentExist(array $request, ?ItemHistory $history, string $paymentHash)
    {
        if ($this->isPaymentNotExist($request, $history, $paymentHash) || $history->getStatus() !== PaymentStatusEnum::CREATED) {
            throw new RuntimeException('This payment is not exist! If it is not right pleas contact with your administrator.');
        }
    }

    private function isPaymentNotExist(array $request, ?ItemHistory $history, string $paymentHash): bool
    {
        return !$history || !$request['sign'] || $this->getHash($request, $paymentHash) !== $request['sign'];
    }

    private function getHash(array $data, string $hash): string
    {
        return md5($data['service'] . $data['orderid'] . $data['amount'] . $data['userdata'] . $data['status'] . $hash);
    }

    public function validate(array $request)
    {
        if (!isset($request["userdata"])) {
            throw new RuntimeException();
        }

        if (!isset($request["status"])) {
            throw new RuntimeException();
        }

        if (!isset($request["sign"])) {
            throw new RuntimeException();
        }
    }
}