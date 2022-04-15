<?php

namespace App\Controller\Client;

use App\Entity\Item;
use App\Enum\PaymentStatusEnum;
use App\Form\ItemType;
use App\Form\VoucherType;
use App\Repository\ItemHistoryRepository;
use App\Repository\ItemRepository;
use App\Service\Payment\Request\RequestBuilder;
use App\Service\PaySafeCardManualService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\SyntaxError;

class ItemController extends AbstractRenderController
{
    /**
     * @Route (name="shop", path="/shop")
     * @throws SyntaxError
     */
    public function shop(ItemRepository $repository, ItemHistoryRepository $itemHistoryRepository): Response
    {
        $form = $this->createForm(VoucherType::class);

        return $this->render('client/shop.html.twig', [
            'items' => $repository->findAll(),
            'groupItems' => $repository->groupByServer(),
            'lastBuyers' => $itemHistoryRepository->findBy(['status' => PaymentStatusEnum::REALIZED], null, 10),
            'voucherForm' => $form->createView(),
            'targetProgress' => $itemHistoryRepository->getProgressOfTarget()
        ]);
    }

    /**
     * @Route (name="item", path="/shop/{id}")
     * @throws ORMException|OptimisticLockException|SyntaxError
     */
    public function item(Item $item, Request $request, RequestBuilder $builder, PaySafeCardManualService $manualService): Response
    {
        $form = $this->createForm(ItemType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('payment') === 'paySafeCard') {
                return $this->redirectToRoute('pscPending', ['hash' => $manualService->createManualPSC($form, $item)->getHash()]);
            }

            return $builder->get($form->getData(), $item, function ($parameters) {
                return $this->render('client/payment.html.twig', $parameters);
            });
        }

        return $this->render('client/item.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}