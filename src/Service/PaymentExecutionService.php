<?php

namespace App\Service;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
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

    /** @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException|InvalidArgumentException */
    public function execute(array $payment): ?string
    {
        /** @var ?ItemHistory $history */
        $history = $this->historyRepository->find($payment['ID_ZAMOWIENIA']);
        $paymentStatus = $payment['STATUS'] * 10;

        if ($this->isPaymentExist($payment, $history)) {
            return 'This payment is not exist! If it is not right pleas contact with your administrator.';
        }
        if ($paymentStatus !== PaymentStatusEnum::ACCEPTED) {
            $history->setStatus($paymentStatus);
            $this->historyRepository->insertOrUpdate($history);

            return 'This payment is not accepted. If it is not right pleas contact with your administrator.';
        }
        if (!$this->service->isPlayerLoggedIn($history->getUsername())) {
            $history->setStatus(PaymentStatusEnum::NOT_ON_SERVER);
            $this->historyRepository->insertOrUpdate($history);

            return 'You are not connected to the server. If it is not right pleas contact with your administrator.';
        }

        foreach ($history->getItem()->getCommand() as $command) {
            $this->service->execute($command, $history->getItem()->getServer());
        }

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return null;
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