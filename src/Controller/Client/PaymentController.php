<?php

namespace App\Controller\Client;

use App\Entity\PaySafeCardVoucher;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentTypeEnum;
use App\Enum\PaySafeCardStatusEnum;
use App\Form\PaymentStatusType;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Service\Payment\PaymentExecutionService;
use App\Service\Voucher\VoucherAssignService;
use App\Service\Voucher\VoucherExecutionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\SyntaxError;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

class PaymentController extends AbstractRenderController
{
    /**
     * @Route(name="status", path="/payment/status")
     * @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException|InvalidArgumentException
     */
    public function status(Request $request, PaymentExecutionService $executionService): Response
    {
        $form = $this->createForm(PaymentStatusType::class);
        $form->submit($request->request->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            new JsonResponse(['message' => 'Wrong data given'], Response::HTTP_BAD_REQUEST);
        }

        $message = $executionService->execute($form->getData());

        return new JsonResponse(['message' => $message]);
    }

    /**
     * @Route(name="voucher", path="/payment/voucher")
     * @throws AuthenticationException|InvalidArgumentException|InvalidPacketException|ORMException|OptimisticLockException|SyntaxError
     */
    public function voucher(Request $request, VoucherExecutionService $executionService): Response
    {
        $form = $this->createForm(VoucherType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('client/rejected.html.twig', [
                'message' => 'Wrong data given',
            ]);
        }

        if ($message = $executionService->execute($form->getData())) {
            return $this->render('client/rejected.html.twig', [
                'message' => $message,
            ]);
        }

        return $this->render('client/success.html.twig');
    }

    /**
     * @Route(name="thankYoy", path="/payment")
     * @throws SyntaxError
     */
    public function payment(Request $request, ItemHistoryRepository $itemHistoryRepository): Response
    {
        $cookies = $request->cookies;
        $itemHistory = $itemHistoryRepository->find($cookies->get('paymentId', 0));

        switch ($itemHistory->getStatus()) {
            case PaymentStatusEnum::CREATED:
            case PaymentStatusEnum::UNACCEPTED:
            case PaymentStatusEnum::CANCELED:
            case "FAILURE":
                $message = 'This payment is not accepted. If it is not right pleas contact with your administrator.';
                break;
            case "PENDING":
                $message = 'This payment is still pending. Pleas not log out from server.';
                break;
            case PaymentStatusEnum::TIME_OUT:
                $message = 'This payment can not checked correctly. Pleas contact with administrator.';
                break;
            case PaymentStatusEnum::NOT_ON_SERVER:
                $message = "You are not connected to serwer! Contact with administration and give him this payment ID";
                break;
            case PaymentStatusEnum::REALIZED:
                $message = "Payment realized successfully.";
                break;
            case PaymentStatusEnum::NOT_EXISTED:
                $message = 'Given payment does not exist.';
                break;
        }

        return $this->render('client/thankYou.html.twig', [
            'type' => $itemHistory->getType(),
            'message' => $message ?? '',
            'paymentId' => $cookies->get('paymentId', 0),
        ]);
    }

    /**
     * @Route(name="pscVoucher", path="/paySafeCard/{pscVoucher}")
     * @throws SyntaxError|ORMException|OptimisticLockException
     */
    public function getVoucher(?PaySafeCardVoucher $pscVoucher, VoucherAssignService $assignService): Response
    {
        if (!$pscVoucher || $pscVoucher->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NOT_WORKING) {
            return $this->render('client/rejected.html.twig', [
                'message' => "Payment not exist ore removed.",
            ]);
        }

        if ($pscVoucher->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NEW) {
            return $this->render('client/thankYou.html.twig', [
                'type' => PaymentTypeEnum::PAY_SAFE_CARD,
                'message' => 'This payment is still pending. Please wait little bit more ore contact with administration on the server.',
                'paymentId' => $pscVoucher->getPaySafeCard()->getId(),
            ]);
        }

        $assignService->assign($pscVoucher);

        return $this->render('client/thankYou.html.twig', [
            'type' => PaymentTypeEnum::PAY_SAFE_CARD,
            'message' => 'Payment realized successfully. Get your voucher.',
            'voucher' => $pscVoucher->getVoucher()->getCode(),
            'paymentId' => $pscVoucher->getPaySafeCard()->getId(),
        ]);
    }
}