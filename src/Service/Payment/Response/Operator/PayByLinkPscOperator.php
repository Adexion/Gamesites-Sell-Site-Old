<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

class PayByLinkPscOperator extends OperatorAbstract implements OperatorInterface
{
    protected const SUCCESSFULLY_STATUES = [true];
    protected const FAILURE_STATUSES = [false];

    /** @throws ORMException|OptimisticLockException */
    public function getResponse(array $request): ?string
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['control']]);
        $paymentHash = $this->historyRepository->getPaymentHash($history);
        $paymentStatus = $request['status'];

        $this->handlePaymentExist($request, $history, $paymentHash);

        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        $this->execute($history);

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return 'OK';
    }

    private function handlePaymentExist(array $request, ?ItemHistory $history, string $paymentHash)
    {
        if ($this->isPaymentNotExist($request, $history, $paymentHash) || $history->getStatus() !== PaymentStatusEnum::CREATED) {
            throw new RuntimeException('This payment is not exist! If it is not right pleas contact with your administrator.');
        }
    }

    private function isPaymentNotExist(array $request, ?ItemHistory $history, string $paymentHash): bool
    {
        return !$history || !$request['hashsha256'] || $this->getHash($request, $paymentHash) !== $request['hashsha256'];
    }

    private function getHash(array $data, string $hash): string
    {
        return hash("sha256", $data['secret']. $hash . $data['amount']);
    }

    public function validate(array $request)
    {
        if (!isset($request["control"])) {
            throw new RuntimeException();
        }

        if (!isset($request["status"])) {
            throw new RuntimeException();
        }

        if (!isset($request["hashsha256"])) {
            throw new RuntimeException();
        }
    }
}