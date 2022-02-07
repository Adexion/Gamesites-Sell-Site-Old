<?php

namespace App\Service;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentTypeEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

class PaymentExecutionService
{
    private ItemHistoryRepository $historyRepository;
    private PaymentRepository $paymentRepository;
    private QueryService $service;
    private ?string $hash;

    public function __construct(ItemHistoryRepository $historyRepository, PaymentRepository $paymentRepository, QueryService $service)
    {
        $this->historyRepository = $historyRepository;
        $this->paymentRepository = $paymentRepository;
        $this->service = $service;
    }

    /** @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException|InvalidArgumentException */
    public function execute(array $payment): ?string
    {
        /** @var ?ItemHistory $history */
        $history = $this->historyRepository->find($payment['ID_ZAMOWIENIA']);
        if (in_array($history->getType(), PaymentTypeEnum::values())) {
            $this->hash = $this->paymentRepository->findOneBy(['type' => $history->getType(), 'id' => $history->getPaymentId()])->getHash();
        }

        /** @var int|string $paymentStatus */
        $paymentStatus = $payment['STATUS'];

        if ($this->isPaymentNotExist($payment, $history) || $history->getStatus() !== PaymentStatusEnum::CREATED) {
            return 'This payment is not exist! If it is not right pleas contact with your administrator.';
        }
        if (!in_array($paymentStatus, ["SUCCESS", PaymentStatusEnum::ACCEPTED])) {
            if (in_array($paymentStatus, ["FAILURE", PaymentStatusEnum::PENDING])) {
                $paymentStatus = PaymentStatusEnum::UNACCEPTED;
            }

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
            $this->service->execute($command, $history->getItem()->getServer(), $history->getUsername());
        }

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return null;
    }

    private function isPaymentNotExist(array $payment, ?ItemHistory $history): bool
    {
        return !$history
            || ($payment['HASH'] && $this->getHash($payment) !== $payment['HASH']);
    }

    private function getHash(array $data): string
    {
        return hash("sha256",
            $this->hash.";"
            .$data["KWOTA"].";"
            .$data["ID_PLATNOSCI"].";"
            .$data["ID_ZAMOWIENIA"].";"
            .$data["STATUS"].";"
            .$data["SEKRET"]
        );
    }
}