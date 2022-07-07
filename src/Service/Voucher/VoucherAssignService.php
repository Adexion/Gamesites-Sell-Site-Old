<?php

namespace App\Service\Voucher;

use App\Entity\PaySafeCardVoucher;
use App\Entity\Voucher;
use App\Repository\PaySafeCardVoucherRepository;
use App\Repository\VoucherRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class VoucherAssignService
{
    private PaySafeCardVoucherRepository $pscVoucherRepository;
    private VoucherRepository $voucherRepository;

    public function __construct(
        PaySafeCardVoucherRepository $pscVoucherRepository,
        VoucherRepository $voucherRepository
    ) {
        $this->pscVoucherRepository = $pscVoucherRepository;
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @throws OptimisticLockException|ORMException
     */
    public function assign(PaySafeCardVoucher $pscVoucher)
    {
        if (!$pscVoucher->getVoucher()) {
            $voucher = (new Voucher())
                ->setDate(new DateTime('+1 month'))
                ->setTimes(1)
                ->setItem($pscVoucher->getPaySafeCard()->getItem())
                ->setCode(uniqid('MG', true));

            $this->voucherRepository->insertOrUpdate($voucher);

            $pscVoucher->setVoucher($voucher);
            $this->pscVoucherRepository->insertOrUpdate($pscVoucher);
        }
    }
}