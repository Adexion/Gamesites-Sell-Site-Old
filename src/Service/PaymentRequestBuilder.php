<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Repository\ItemHistoryRepository;
use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class PaymentRequestBuilder
{
    private ItemHistoryRepository $historyRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(ItemHistoryRepository $historyRepository, PaymentRepository $paymentRepository)
    {
        $this->historyRepository = $historyRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /** @throws OptimisticLockException|ORMException */
    public function buildResponse(array $data, Item $item): array
    {
        $data['payment'] = $this->paymentRepository->findOneBy(['isActive' => true, 'type' => $data['payment']]);

        $history = $this->createHistory($data, $item);

        return [
            'ID_ZAMOWIENIA' => $history->getId(),
            'NAZWA_USLUGI' => $item->getName(),
            'SEKRET' => $data['payment']->getSecret(),
            'KWOTA' => $item->getDiscountedPrice(),
            'EMAIL' => $data['email'],
            'ADRES_WWW' => sprintf('%s/payment', $data['uri']),
            'PRZEKIEROWANIE_SUKCESS' => sprintf('%s/payment', $data['uri']),
            'PRZEKIEROWANIE_BLAD' => sprintf('%s/payment', $data['uri']),
        ];
    }

    /** @throws OptimisticLockException|ORMException */
    private function createHistory(array $data, Item $item): ItemHistory
    {
        $history = (new ItemHistory())
            ->setDate(new DateTime())
            ->setItem($item)
            ->setEmail($data['email'])
            ->setPaymentId($data['payment']->getId())
            ->setStatus(0)
            ->setType($data['payment']->getType())
            ->setUsername($data['username'])
            ->setPrice($item->getDiscountedPrice());

        return $this->historyRepository->insertOrUpdate($history);
    }
}