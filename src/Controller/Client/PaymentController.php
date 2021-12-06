<?php

namespace App\Controller\Client;

use App\Enum\PaymentStatusEnum;
use App\Form\PaymentStatusType;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Service\PaymentExecutionService;
use App\Service\VoucherExecutionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class PaymentController extends AbstractController
{
    /**
     * @Route(name="status", path="/payment/status")
     *
     * @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException|InvalidArgumentException
     */
    public function status(Request $request, PaymentExecutionService $executionService): Response
    {
        $form = $this->createForm(PaymentStatusType::class);
        $form->submit($request->request->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('client/rejected.html.twig', [
                'message' => 'Wrong data given',
            ]);
        }

        $message = $executionService->execute($form->getData());

        return new JsonResponse(['message' => $message]);
    }

    /**
     * @Route(name="voucher", path="/payment/voucher")
     *
     * @throws AuthenticationException|InvalidArgumentException|InvalidPacketException|ORMException|OptimisticLockException
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
     * @Route(name="thankYoy", path="/payment/")
     */
    public function payment(Request $request, ItemHistoryRepository $itemHistoryRepository)
    {
        $cookies = $request->cookies;
        $itemHistory = $itemHistoryRepository->find($cookies->get('paymentId', 0));

        switch ($itemHistory->getStatus()) {
            case PaymentStatusEnum::CREATED:
            case PaymentStatusEnum::UNACCEPTED:
            case PaymentStatusEnum::FAILURE:
            case PaymentStatusEnum::CANCELED:
                $message = 'This payment is not accepted. If it is not right pleas contact with your administrator.';
                break;
            case PaymentStatusEnum::PENDING:
                $message = 'This payment is still pending. Pleas not log out from server.';
                break;
            case PaymentStatusEnum::TIME_OUT:
                $message = 'This payment can not checked correctly. Pleas contact with administrator.';
                breAK;
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
            'message' => $message ?? '',
            'paymentId' => $cookies->get('paymentId', 0)
        ]);
    }
}