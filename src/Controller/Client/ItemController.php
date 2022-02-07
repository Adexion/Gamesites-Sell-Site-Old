<?php

namespace App\Controller\Client;

use App\Entity\Item;
use App\Entity\PaySafeCardVoucher;
use App\Enum\PaymentStatusEnum;
use App\Enum\PaymentTypeEnum;
use App\Form\ItemType;
use App\Form\PaymentType;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Repository\ItemRepository;
use App\Service\PaymentRequestBuilder;
use App\Service\PaySafeCardManualService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\SyntaxError;

class ItemController extends AbstractRenderController
{
    /** @Route (name="shop", path="/shop") */
    public function shop(ItemRepository $repository, ItemHistoryRepository $itemHistoryRepository): Response
    {
        $form = $this->createForm(VoucherType::class);

        return $this->render('client/shop.html.twig', [
            'items' => $repository->findAll(),
            'groupItems' => $repository->groupByServer(),
            'lastBuyers' => $itemHistoryRepository->findBy(['status' => PaymentStatusEnum::REALIZED], null, 10),
            'voucherForm' => $form->createView(),
        ]);
    }

    /** @Route (name="item", path="/shop/{id}") */
    public function item(Item $item, Request $request, PaymentRequestBuilder $builder, PaySafeCardManualService $manualService): Response
    {
        $form = $this->createForm(ItemType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('payment') === 'paySafeCard') {
                $voucher = $manualService->createManualPSC($form, $item);

                return $this->redirectToRoute('voucher', ['hash' => $voucher->getHash()]);
            }

            return $this->paymentBuilder($item, $builder, $form);
        }

        return $this->render('client/item.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /** @Route (name="voucher", path="/shop/{hash}/voucher") */
    public function voucher(PaySafeCardVoucher $voucher): Response {
        return $this->render('client/thankYou.html.twig', [
            'type' => PaymentTypeEnum::PAY_SAFE_CARD,
            'message' => 'PaySafeCard is pending. Contact with administrator',
            'paymentId' => $voucher->getPaySafeCard()->getId(),
            'link' => $this->generateUrl('pscVoucher', ['hash' => $voucher->getHash()]),
        ]);
    }

    /** @throws OptimisticLockException|SyntaxError|ORMException */
    private function paymentBuilder(Item $item, PaymentRequestBuilder $builder, FormInterface $form): Response
    {
        $payment = $builder->buildRequest($form->getData(), $item);

        $redirectForm = $this->createForm(PaymentType::class);
        $redirectForm->submit($payment);

        $response = $this->render('client/payment.html.twig', [
            'form' => $redirectForm->createView(),
            'paymentType' => $form->getData()['payment'],
        ]);

        $cookie = new Cookie('paymentId', $payment['ID_ZAMOWIENIA'], strtotime('now + 30 minutes'));
        $response->headers->setCookie($cookie);

        return $response;
    }
}