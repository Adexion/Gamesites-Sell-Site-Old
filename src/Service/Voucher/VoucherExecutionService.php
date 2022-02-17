<?php

namespace App\Service\Voucher;

use App\Entity\ItemHistory;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemHistoryRepository;
use App\Repository\VoucherRepository;
use App\Service\Connection\ExecuteServiceFactory;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;

class VoucherExecutionService
{
    private ItemHistoryRepository $historyRepository;
    private ExecuteServiceFactory $factory;
    private VoucherRepository $voucherRepository;

    public function __construct(
        ItemHistoryRepository $historyRepository,
        VoucherRepository $voucherRepository,
        ExecuteServiceFactory $factory
    ) {
        $this->historyRepository = $historyRepository;
        $this->factory = $factory;
        $this->voucherRepository = $voucherRepository;
    }

    /** @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|InvalidArgumentException */
    public function execute(array $data): ?string
    {
        $voucher = $this->voucherRepository->findOneBy(['code' => $data['code']]);
        if (!$voucher || $voucher->getDate()->format('Y-m-d') < date('Y-m-d')) {
            return 'Voucher is not valid or expired';
        }

        $count = count($this->historyRepository->findBy(['type' => 'voucher', 'paymentId' => $voucher->getId()]));
        if (($voucher->getTimes() > 0 && $count === $voucher->getTimes()) || $voucher->getTimes() === 0) {
            return 'Voucher used too match times';
        }

        $service = $this->factory->getExecutionService($voucher->getItem()->getServer());
        if (!$service->isPlayerLoggedIn($data['username'])) {
            return 'You are not connected to the server. If it is not right pleas contact with your administrator.';
        }

        foreach ($voucher->getItem()->getCommand() as $command) {
            try {
                $service->execute($command, $data['username']);
            } catch (Exception $ignored) {
                return 'There was a problem connecting to the server. Please contact your administrator.';
            }
        }

        $history = new ItemHistory();

        $history->setUsername($data['username']);
        $history->setEmail($data['email']);
        $history->setPaymentId($voucher->getId());
        $history->setType('voucher');
        $history->setStatus(PaymentStatusEnum::REALIZED);
        $history->setItem($voucher->getItem());
        $history->setDate(new DateTime());

        $this->historyRepository->insertOrUpdate($history);

        return null;
    }
}