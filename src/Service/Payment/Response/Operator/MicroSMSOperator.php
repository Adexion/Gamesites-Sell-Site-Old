<?php

namespace App\Service\Payment\Response\Operator;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use RuntimeException;

final class MicroSMSOperator extends OperatorAbstract implements OperatorInterface
{
    protected const SUCCESSFULLY_STATUES = ["TRUE"];
    protected const FAILURE_STATUSES = ["FALSE"];

    /** @throws OptimisticLockException|ORMException */
    public function getResponse(array $request): ?string
    {
        parent::getResponse($request);

        $history = $this->historyRepository->findOneBy(['id' => $request['control']]);
        $paymentStatus = $request['status'];

        $this->handlePaymentExist($history);
        $this->handleUnsuccessfullyResponse($paymentStatus, $history);
        $this->handleNotOnServerResponse($history);

        $this->execute($history);
        $history->setStatus(PaymentStatusEnum::REALIZED);
        $this->historyRepository->insertOrUpdate($history);

        return 'OK';
    }

    public function validate(array $request)
    {
        if (!isset($request["status"])) {
            throw new RuntimeException();
        }

        if (!isset($request["orderID"])) {
            throw new RuntimeException();
        }

        if (!isset($request["control"])) {
            throw new RuntimeException();
        }
    }

    private function handlePaymentExist(?ItemHistory $history)
    {
        if (!$history){
            throw new RuntimeException('This payment is not exist! If it is not right pleas contact with your administrator.');
        }
    }
}