<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\PaySafeCard;
use App\Entity\PaySafeCardVoucher;
use App\Repository\PaySafeCardRepository;
use App\Repository\PaySafeCardVoucherRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\Form\FormInterface;

class PaySafeCardManualService
{
    private PaySafeCardRepository $paySafeCardRepository;
    private PaySafeCardVoucherRepository $paySafeCardVoucherRepository;

    public function __construct(
        PaySafeCardRepository $paySafeCardRepository,
        PaySafeCardVoucherRepository $paySafeCardVoucherRepository
    ) {
        $this->paySafeCardRepository = $paySafeCardRepository;
        $this->paySafeCardVoucherRepository = $paySafeCardVoucherRepository;
    }

    /** @throws OptimisticLockException|ORMException */
    public function createManualPSC(FormInterface $form, Item $item): PaySafeCardVoucher
    {
        if ($psc = $this->paySafeCardRepository->findOneBy(['code' => $form->getData()['code']])) {
            return $this->paySafeCardVoucherRepository->findOneBy(['paySafeCard' => $psc]);
        }

        $psc = (new PaySafeCard())
            ->setItem($item)
            ->setDate(new DateTime())
            ->setEmail($form->getData()['email'])
            ->setUsername($form->getData()['username'])
            ->setCode($form->getData()['code'])
            ->setStatus(false);

        $this->paySafeCardRepository->insertOrUpdate($psc);

        $hash = hash('sha1', date('Y-m-d H:i:s'));
        $pscVoucher = (new PaySafeCardVoucher())
            ->setHash($hash)
            ->setPaySafeCard($psc);

        $this->paySafeCardVoucherRepository->insertOrUpdate($pscVoucher);

        return $pscVoucher;
    }
}