<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

final class TPayOperator extends OperatorAbstract implements OperatorInterface
{
    protected const SUCCESSFULLY_STATUES = ['TRUE', 'PAID'];
    protected const FAILURE_STATUSES = ['FALSE', 'CHARGEBACK'];

    /** @throws ORMException|OptimisticLockException */
    public function getResponse(array $request): ?string
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['tr_crc']]);
        $paymentHash = $this->historyRepository->getPaymentHash($history);

        $paymentStatus = $request['tr_status'];

        $this->handlePaymentExist($request, $history, $paymentHash);

        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        $this->execute($history);

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return 'TRUE';
    }

    private function handlePaymentExist(array $request, ?ItemHistory $history, string $paymentHash)
    {
        if ($this->isPaymentNotExist($request, $history, $paymentHash) || $history->getStatus() !== PaymentStatusEnum::CREATED) {
            throw new RuntimeException('This payment is not exist! If it is not right pleas contact with your administrator.');
        }
    }

    private function isPaymentNotExist(array $request, ?ItemHistory $history, string $paymentHash): bool
    {
        return !$history || !$request['md5sum'] || $this->getHash($request, $paymentHash) !== $request['md5sum'];
    }

    private function getHash(array $request, string $hash): string
    {
        return md5($request['id'] . $request['tr_id '] . $request['tr_amount'] . $request['tr_crc'] . $hash);
    }

    public function validate(array $request)
    {
        if (!isset($request["tr_id"])) {
            throw new RuntimeException();
        }

        if (!isset($request["id"])) {
            throw new RuntimeException();
        }
    }
}