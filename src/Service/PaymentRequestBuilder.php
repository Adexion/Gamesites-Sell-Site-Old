<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Entity\Payment;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentRequestBuilder
{
    private ItemHistoryRepository $historyRepository;
    private PaymentRepository $paymentRepository;
    private ?Request $request;

    public function __construct(
        ItemHistoryRepository $historyRepository,
        PaymentRepository $paymentRepository,
        RequestStack $requestStack
    ) {
        $this->historyRepository = $historyRepository;
        $this->paymentRepository = $paymentRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    /** @throws OptimisticLockException|ORMException */
    public function buildRequest(array $data, Item $item): array
    {
        $payment = $this->paymentRepository->findOneBy(['isActive' => true, 'type' => $data['payment']]);

        $history = $this->createHistory($data, $item, $payment);
        $uri = $this->request->getSchemeAndHttpHost();

        return [
            'ID_ZAMOWIENIA' => $history->getId(),
            'NAZWA_USLUGI' => $item->getName(),
            'SEKRET' => $payment->getSecret(),
            'KWOTA' => $item->getDiscountedPrice(),
            'EMAIL' => $data['email'],
            'ADRES_WWW' => sprintf('%s/payment', $uri),
            'PRZEKIEROWANIE_SUKCESS' => sprintf('%s/payment', $uri),
            'PRZEKIEROWANIE_BLAD' => sprintf('%s/payment', $uri),
        ];
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