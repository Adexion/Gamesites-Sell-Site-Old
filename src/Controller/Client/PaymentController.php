<?php

namespace App\Controller\Client;

use App\Entity\PaySafeCard;
use App\Entity\PaySafeCardVoucher;
use App\Entity\Voucher;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentTypeEnum;
use App\Enum\PaySafeCardStatusEnum;
use App\Form\PaymentStatusType;
use App\Form\PaySafeCardType;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Service\PaymentExecutionService;
use App\Service\VoucherExecutionService;
use DateTime;
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

    /** @Route(name="thankYoy", path="/payment/") */
    public function payment(Request $request, ItemHistoryRepository $itemHistoryRepository): Response
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

    /** @Route(name="paySafeCard", path="/paySafeCard/") */
    public function paySafeCard(Request $request): Response
    {
        $form = $this->createForm(PaySafeCardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData()['item'];
            if ($this->getDoctrine()->getRepository(PaySafeCard::class)->findOneBy(['code' => $form->getData()['code']])) {
                $this->addFlash('error', 'Given wrong data in PSC form');

                return $this->redirectToRoute('item', ['id' => $form->getData()['item']->getId()]);
            }

            $psc = (new PaySafeCard())
                ->setItem($item)
                ->setDate(new DateTime())
                ->setEmail($form->getData()['email'])
                ->setUsername($form->getData()['username'])
                ->setCode($form->getData()['code'])
                ->setStatus(false);

            $this->getDoctrine()->getManager()->persist($psc);
            $this->getDoctrine()->getManager()->flush();

            $hash = hash('sha1', date('Y-m-d H:i:s'));
            $pscVoucher = (new PaySafeCardVoucher())
                ->setHash($hash)
                ->setPaySafeCard($psc);

            $this->getDoctrine()->getManager()->persist($pscVoucher);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('client/thankYou.html.twig', [
                'type' => PaymentTypeEnum::PAY_SAFE_CARD,
                'message' => 'PaySafeCard is pending. Contact with administrator',
                'paymentId' => $psc->getId(),
                'link' => $this->generateUrl('pscVoucher', ['hash' => $hash]),
            ]);
        }

        $this->addFlash('error', 'Given wrong data in PSC form');

        return $this->redirectToRoute('item', ['id' => $form->getData()['item']->getId()]);
    }

    /** @Route(name="pscVoucher", path="/paySafeCard/{hash}") */
    public function getVoucher(?PaySafeCardVoucher $hash): Response
    {
        if (!$hash || $hash->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NOT_WORKING) {
            return $this->render('client/rejected.html.twig', [
                'message' => "Payment not exist ore removed."
            ]);
        }

        if ($hash->getPaySafeCard()->getStatus() === PaySafeCardStatusEnum::NEW) {
            return $this->render('client/thankYou.html.twig', [
                'type' => PaymentTypeEnum::PAY_SAFE_CARD,
                'message' => 'This payment is still pending. Please wait little bit more ore contact with administration on the server.',
                'paymentId' => $hash->getPaySafeCard()->getId(),
            ]);
        }

        if (!$hash->getVoucher()) {
            $voucher = (new Voucher())
                ->setDate(new DateTime())
                ->setTimes(1)
                ->setItem($hash->getPaySafeCard()->getItem())
                ->setCode(uniqid('MG', true));

            $this->getDoctrine()->getManager()->persist($voucher);
            $this->getDoctrine()->getManager()->flush();

            $hash->setVoucher($voucher);

            $this->getDoctrine()->getManager()->persist($hash);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('client/thankYou.html.twig', [
            'type' => PaymentTypeEnum::PAY_SAFE_CARD,
            'message' => 'Payment realized successfully. Get your voucher.',
            'voucher' => $hash->getVoucher()->getCode(),
            'paymentId' => $hash->getPaySafeCard()->getId(),
        ]);
    }
}