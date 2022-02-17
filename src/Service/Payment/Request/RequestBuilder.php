<?php

namespace App\Service\Payment\Request;

use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Entity\Payment;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class RequestBuilder
{
    private OperatorFactory $operatorFactory;
    private PaymentRepository $paymentRepository;
    private ItemHistoryRepository $historyRepository;

    public function __construct(OperatorFactory $operatorFactory, PaymentRepository $paymentRepository, ItemHistoryRepository $historyRepository)
    {
        $this->operatorFactory = $operatorFactory;
        $this->paymentRepository = $paymentRepository;
        $this->historyRepository = $historyRepository;
    }

    /** @throws OptimisticLockException|ORMException */
    public function get(array $data, Item $item, callable $callable): Response
    {
        $payment = $this->paymentRepository->findOneBy(['isActive' => true, 'type' => $data['payment']]);
        $history = $this->createHistory($data, $item, $payment);

        $response = $callable([
            'form' => $this->operatorFactory->getForm($data, $item, $history->getId(), $payment->getSecret(), $payment->getHash())->createView(),
            'paymentType' => $data['payment'],
        ]);

        $cookie = new Cookie('paymentId', $history->getId(), strtotime('now + 30 minutes'));
        $response->headers->setCookie($cookie);

        return $response;
    }

    /** @throws ORMException|OptimisticLockException */
    private function createHistory(array $data, Item $item, ?Payment $payment): ItemHistory
    {
        $history = (new ItemHistory())
            ->setDate(new DateTime())
            ->setItem($item)
            ->setStatus(PaymentStatusEnum::CREATED)
            ->setEmail($data['email'])
            ->setUsername($data['username'])
            ->setPrice($item->getDiscountedPrice())
            ->setPaymentId($payment->getId())
            ->setType($payment->getType());

        return $this->historyRepository->insertOrUpdate($history);
    }
}