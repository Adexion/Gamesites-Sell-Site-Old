<?php

namespace App\Service;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

class PaymentExecutionService
{
    private ItemHistoryRepository $historyRepository;
    private QueryService $service;

    public function __construct(ItemHistoryRepository $historyRepository, QueryService $service)
    {
        $this->historyRepository = $historyRepository;
        $this->service = $service;
    }

    /** @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException */
    public function execute(array $payment): int
    {
        /** @var ?ItemHistory $history */
        $history = $this->historyRepository->find($payment['ID_ZAMOWIENIA']);
        $paymentStatus = $payment['STATUS'] * 10;

        if ($this->isPaymentExist($payment, $history)) {
            return PaymentStatusEnum::NOT_EXISTED;
        }
        if ($paymentStatus !== PaymentStatusEnum::ACCEPTED) {
            $history->setStatus($paymentStatus);
            $this->historyRepository->insertOrUpdate($history);

            return $paymentStatus;
        }
        if (!$this->service->isPlayerLoggedIn($history->getUsername())) {
            $history->setStatus(PaymentStatusEnum::NOT_ON_SERVER);
            $this->historyRepository->insertOrUpdate($history);

            return PaymentStatusEnum::ACCEPTED;
        }

        foreach ($history->getItem()->getCommand() as $command) {
            $this->service->execute($command);
        }

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return PaymentStatusEnum::REALIZED;
    }

    private function isPaymentExist(array $payment, ?ItemHistory $history): bool
    {
        return !$history
            || ($payment['HASH'] && $this->getHash($payment) !== $payment['HASH']);
    }

    private function getHash(array $data): string
    {
        return hash("sha256", "HASHZUSTAWIEN;"
            .$data["KWOTA"].";"
            .$data["ID_PLATNOSCI"].";"
            .$data["ID_ZAMOWIENIA"].";"
            .$data["STATUS"].";"
            .$data["SEKRET"]
        );
    }
}