<?php

namespace App\Controller\Client;

use App\Entity\PaySafeCardVoucher;
use App\Enum\OperatorTypeEnum;
use App\Enum\PaymentFullyStatusEnum;
use App\Enum\PaymentStatusResponseEnum;
use App\Enum\PaySafeCardStatusEnum;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Service\Voucher\VoucherAssignService;
use App\Service\Voucher\VoucherExecutionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\SyntaxError;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;

class PaymentController extends AbstractRenderController
{
    /**
     * @Route(name="voucher", path="/payment/voucher")
     * @throws AuthenticationException|InvalidArgumentException|InvalidPacketException|ORMException|OptimisticLockException|SyntaxError
     */
    public function voucher(Request $request, VoucherExecutionService $executionService): Response
    {
        $form = $this->createForm(VoucherType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->renderTheme('rejected.html.twig', [
                'message' => 'Wrong data given',
            ]);
        }

        if ($message = $executionService->execute($form->getData())) {
            return $this->renderTheme('rejected.html.twig', [
                'message' => $message,
            ]);
        }

        return $this->renderTheme('success.html.twig');
    }

    /**
     * @Route(name="thankYoy", path="/payment")
     * @throws SyntaxError
     */
    public function payment(Request $request, ItemHistoryRepository $itemHistoryRepository): Response
    {
        $cookies = $request->cookies;
        $itemHistory = $itemHistoryRepository->find($cookies->get('paymentId', 0));

        return $this->renderTheme('thankYou.html.twig', [
            'type' => $itemHistory->getType(),
            'message' => PaymentStatusResponseEnum::toArray()[PaymentFullyStatusEnum::from(
                    $itemHistory->getStatus()
                )->getKey()] ?? '',
            'paymentId' => $cookies->get('paymentId', 0),
        ]);
    }

    /**
     * @Route (name="pscPending", path="/payment/{hash}/paySafeCard")
     * @throws SyntaxError
     */
    public function pscPending(PaySafeCardVoucher $voucher): Response
    {
        return $this->renderTheme('thankYou.html.twig', [
            'type' => OperatorTypeEnum::PAY_SAFE_CARD,
            'message' => 'PaySafeCard is pending. Contact with administrator',
            'paymentId' => $voucher->getPaySafeCard()->getId(),
            'link' => $this->generateUrl('pscVoucher', ['pscVoucher' => $voucher->getHash()]),
        ]);
    }

    /**
     * @Route(name="pscVoucher", path="/paySafeCard/{pscVoucher}")
     * @throws SyntaxError|ORMException|OptimisticLockException
     */
    public function getVoucher(?PaySafeCardVoucher $pscVoucher, VoucherAssignService $assignService): Response
    {
        if (!$pscVoucher || $pscVoucher->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NOT_WORKING) {
            return $this->renderTheme('rejected.html.twig', [
                'message' => "Payment not exist ore removed.",
            ]);
        }

        if ($pscVoucher->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NEW) {
            return $this->renderTheme('thankYou.html.twig', [
                'type' => OperatorTypeEnum::PAY_SAFE_CARD,
                'message' => 'This payment is still pending. Please wait little bit more ore contact with administration on the server.',
                'paymentId' => $pscVoucher->getPaySafeCard()->getId(),
            ]);
        }

        $assignService->assign($pscVoucher);

        return $this->renderTheme('thankYou.html.twig', [
            'type' => OperatorTypeEnum::PAY_SAFE_CARD,
            'message' => 'Payment realized successfully. Get your voucher.',
            'voucher' => $pscVoucher->getVoucher()->getCode(),
            'paymentId' => $pscVoucher->getPaySafeCard()->getId(),
        ]);
    }
}