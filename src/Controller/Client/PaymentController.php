<?php

namespace App\Controller\Client;

use App\Enum\PaymentStatusEnum;
use App\Form\PaymentStatusType;
use App\Service\PaymentExecutionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class PaymentController extends AbstractController
{
    /**
     * @Route(name="status", path="/payment/status")
     *
     * @throws AuthenticationException|InvalidPacketException|ORMException|OptimisticLockException|SocketException
     */
    public function status(Request $request, PaymentExecutionService $executionService): Response
    {
        $form = $this->createForm(PaymentStatusType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->render('client/rejected.html.twig');
        }

        $status = $executionService->execute($form->getData());

        return $this->render($status !== PaymentStatusEnum::REALIZED ? 'client/rejected.html.twig' : 'client/success.html.twig');
    }
}