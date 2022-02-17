<?php

namespace App\Service\Payment\Response\Operator;

use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

final class DirectBillingOperator extends OperatorAbstract implements OperatorInterface
{
    protected const SUCCESSFULLY_STATUES = [PaymentStatusEnum::ACCEPTED];
    protected const FAILURE_STATUSES = [PaymentStatusEnum::UNACCEPTED];

    /** @throws OptimisticLockException|ORMException */
    public function getResponse(array $request): ?string
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['ID_ZAMOWIENIA']]);
        $paymentStatus = $request['STATUS'];

        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        $this->execute($history);

        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return 'OK';
    }

    public function validate(array $request): bool
    {
        if (!isset($request["ID_ZAMOWIENIA"])) {
            throw new RuntimeException();
        }

        if (!isset($request["STATUS"])) {
            throw new RuntimeException();
        }
    }
}