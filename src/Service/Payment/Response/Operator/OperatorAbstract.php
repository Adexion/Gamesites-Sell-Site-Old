<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\ServerRepository;
use App\Service\Connection\ExecuteServiceFactory;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

abstract class OperatorAbstract implements OperatorInterface
{
    protected ItemHistoryRepository $historyRepository;
    protected ServerRepository $serverRepository;
    protected ExecuteServiceFactory $factory;

    protected const SUCCESSFULLY_STATUES = [];
    protected const FAILURE_STATUSES = [];

    public function getResponse(array $request): ?string
    {
        return $this->validate($request);
    }

    public function __construct(ItemHistoryRepository $historyRepository, ServerRepository $serverRepository, ExecuteServiceFactory $factory)
    {
        $this->historyRepository = $historyRepository;
        $this->serverRepository = $serverRepository;
        $this->factory = $factory;
    }

    protected function execute(ItemHistory $history)
    {
        foreach ($history->getItem()->getCommand() as $command) {
            $this->factory->getExecutionService($history->getItem()->getServer())->execute(
                $command,
                $history->getUsername(),
                $history->getCount() ?: 1,
            );

            if (str_contains('%amount%', $command) || !$history->getCount()) {
                break;
            }

            for ($i = 0; $i < $history->getCount() - 1; $i++) {
                $this->factory->getExecutionService($history->getItem()->getServer())->execute(
                    $command,
                    $history->getUsername()
                );
            }
        }
    }

    /**  @throws ORMException|OptimisticLockException */
    protected function handleUnsuccessfullyResponse(string $paymentStatus, ItemHistory $history)
    {
        if (!in_array($paymentStatus, $this::SUCCESSFULLY_STATUES)) {
            $paymentStatus = PaymentStatusEnum::UNACCEPTED;

            $history->setStatus($paymentStatus);
            $this->historyRepository->insertOrUpdate($history);

            throw new RuntimeException(
                'This payment is not accepted. If it is not right pleas contact with your administrator.'
            );
        }
    }

    /** @throws ORMException|OptimisticLockException */
    protected function handleNotOnServerResponse(ItemHistory $history)
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

    public abstract function validate(array $request);
}