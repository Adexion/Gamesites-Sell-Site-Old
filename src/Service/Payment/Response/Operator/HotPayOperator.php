<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

class HotPayOperator extends OperatorAbstract implements OperatorInterface
{
    protected const SUCCESSFULLY_STATUES = ["SUCCESS"];
    protected const FAILURE_STATUSES = ["FAILURE", "PENDING"];

    /** @throws ORMException|OptimisticLockException */
    public function getResponse(array $request): ?string
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['ID_ZAMOWIENIA']]);
        $paymentHash = $this->historyRepository->getPaymentHash($history);
        $paymentStatus = $request['STATUS'];

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
        return !$history || !$request['HASH'] || $this->getHash($request, $paymentHash) !== $request['HASH'];
    }

    private function getHash(array $data, string $hash): string
    {
        return hash(
            "sha256",
            $hash . ";"
            . ($data["KWOTA"] ?? '') . ";"
            . ($data["ID_PLATNOSCI"] ?? '') . ";"
            . $data["ID_ZAMOWIENIA"] . ";"
            . ($data["STATUS"] ?? '') . ";"
            . ($data["SEKRET"] ?? '')
        );
    }

    public function validate(array $request)
    {
        if (!isset($request["ID_ZAMOWIENIA"])) {
            throw new RuntimeException();
        }

        if (!isset($request["STATUS"])) {
            throw new RuntimeException();
        }

        if (!isset($request["HASH"])) {
            throw new RuntimeException();
        }
    }
}