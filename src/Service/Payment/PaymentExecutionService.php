<?php

namespace App\Service\Payment;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\ServerRepository;
use App\Service\Connection\ExecuteServiceFactory;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

class PaymentExecutionService
{
    private ItemHistoryRepository $historyRepository;
    private ExecuteServiceFactory $factory;
    private ServerRepository $serverRepository;

    public function __construct(
        ItemHistoryRepository $historyRepository,
        ExecuteServiceFactory $factory,
        ServerRepository $serverRepository
    ) {
        $this->historyRepository = $historyRepository;
        $this->factory = $factory;
        $this->serverRepository = $serverRepository;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function execute(array $payment): ?string
    {
        $data = $this->historyRepository->getHistoryWithHash($payment['ID_ZAMOWIENIA']);
        $paymentStatus = (string)$payment['STATUS'];
        $history = $data['history'];

        if ($this->isPaymentNotExist($payment, $data) || $history->getStatus() !== PaymentStatusEnum::CREATED) {
            return 'This payment is not exist! If it is not right pleas contact with your administrator.';
        }

        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        foreach ($history->getItem()->getCommand() as $command) {
            $this->factory->getExecutionService($history->getItem()->getServer())->execute(
                $command,
                $history->getUsername()
            );
        }

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return null;
    }

    /**  @throws ORMException|OptimisticLockException */
    private function handleUnsuccessfullyResponse(string $paymentStatus, ItemHistory $history)
    {
        if (!in_array($paymentStatus, ["SUCCESS", (string)PaymentStatusEnum::ACCEPTED])) {
            if (in_array($paymentStatus, ["FAILURE", "PENDING"])) {
                $paymentStatus = PaymentStatusEnum::UNACCEPTED;
            }

            $history->setStatus($paymentStatus);
            $this->historyRepository->insertOrUpdate($history);

            throw new RuntimeException(
                'This payment is not accepted. If it is not right pleas contact with your administrator.'
            );
        }
    }

    /**  @throws ORMException|OptimisticLockException */
    private function handleNotOnServerResponse(ItemHistory $history)
    {
        $service = $this->factory->getExecutionService(
            $this->serverRepository->findOneBy(['isDefault' => true]) ?: $history->getItem()->getServer()
        );

        if (!$service->isPlayerLoggedIn($history->getUsername())) {
            $history->setStatus(PaymentStatusEnum::NOT_ON_SERVER);
            $this->historyRepository->insertOrUpdate($history);

            throw new RuntimeException(
                'You are not connected to the server. If it is not right pleas contact with your administrator.'
            );
        }
    }

    private function isPaymentNotExist(array $payment, array $data): bool
    {
        return !$data['history']
            || ($payment['HASH'] && $this->getHash($payment, $data['hash']) !== $payment['HASH']);
    }

    private function getHash(array $data, string $hash): string
    {
        return hash(
            "sha256",
            $hash.";"
            .$data["KWOTA"].";"
            .$data["ID_PLATNOSCI"].";"
            .$data["ID_ZAMOWIENIA"].";"
            .$data["STATUS"].";"
            .$data["SEKRET"]
        );
    }
}