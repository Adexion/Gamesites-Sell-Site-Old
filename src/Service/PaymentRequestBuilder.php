<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Entity\Payment;
use App\Repository\ItemHistoryRepository;
use App\Repository\PaymentRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;

class PaymentRequestBuilder
{
    private ItemHistoryRepository $historyRepository;

    public function __construct( ItemHistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    /** @throws OptimisticLockException|ORMException */
    public function buildResponse(array $data, Item $item): array
    {
        $history = $this->createHistory($data, $item);

        return [
            'ID_ZAMOWIENIA' => $history->getId(),
            'NAZWA_USLUGI' => $item->getName(),
            'SEKRET' => $data['payment']->getSecret(),
            'KWOTA' => $item->getDiscountedPrice(),
            'EMAIL' => $data['email'],
            'ADRES_WWW' => sprintf('%s/%s/payment', $data['uri'], $data['locale']),
            'PRZEKIEROWANIE_SUKCESS' => sprintf('%s/%s/payment', $data['uri'], $data['locale']),
            'PRZEKIEROWANIE_BLAD' => sprintf('%s/%s/payment', $data['uri'], $data['locale']),
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
            ->setUsername($data['username']);

         return $this->historyRepository->insertOrUpdate($history);
    }
}