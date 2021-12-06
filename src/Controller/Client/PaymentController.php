<?php

namespace App\Controller\Client;

use App\Form\PaymentStatusType;
use App\Form\VoucherType;
use App\Service\PaymentExecutionService;
use App\Service\VoucherExecutionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $form->handleRequest($request);
        $form->submit([]);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('client/rejected.html.twig', [
                'message' => 'Wrong data given',
            ]);
        }

        $message = $executionService->execute($form->getData());

        return $this->render($message ? 'client/rejected.html.twig' : 'client/success.html.twig');
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
}