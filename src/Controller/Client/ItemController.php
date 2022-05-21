<?php

namespace App\Controller\Client;

use App\Entity\Item;
use App\Form\ItemType;
use App\Form\VoucherType;
use Twig\Error\SyntaxError;
use Doctrine\ORM\ORMException;
use App\Enum\PaymentStatusEnum;
use App\Repository\ItemRepository;
use App\Repository\ItemHistoryRepository;
use App\Service\PaySafeCardManualService;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Payment\Request\RequestBuilder;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractRenderController
{
    /**
     * @Route (name="shop", path="/shop")
     * @throws SyntaxError
     */
    public function shop(ItemRepository $repository, ItemHistoryRepository $itemHistoryRepository): Response
    {
        $form = $this->createForm(VoucherType::class);
        if ($item = $repository->findOneBy(['isMainItem' => true])) {
            return $this->redirectToRoute('item', ['id' => $item->getId()]);
        }

        return $this->renderTheme('shop.html.twig', [
            'items' => $repository->findAll(),
            'groupItems' => $repository->groupByServer(),
            'lastBuyers' => $itemHistoryRepository->findBy(['status' => PaymentStatusEnum::REALIZED], null, 10),
            'voucherForm' => $form->createView(),
            'targetProgress' => $itemHistoryRepository->getProgressOfTarget(),
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
                return $this->renderTheme('payment.html.twig', $parameters);
            });
        }

        return $this->renderTheme('item.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
            'voucherForm' =>$this->createForm(VoucherType::class)->createView()
        ]);
    }
}