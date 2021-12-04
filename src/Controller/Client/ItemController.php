<?php

namespace App\Controller\Client;

use App\Entity\Item;
use App\Form\ItemType;
use App\Form\PaymentType;
use App\Form\VoucherType;
use App\Repository\ItemRepository;
use App\Service\PaymentRequestBuilder;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class ItemController extends AbstractController
{
    /** @Route (name="shop", path="/shop") */
    public function shop(ItemRepository $repository): Response
    {
        $form = $this->createForm(VoucherType::class);

        return $this->render('client/shop.html.twig', [
            'items' => $repository->findAll(),
            'voucherForm' => $form->createView()
        ]);
    }

    /** @Route (name="item", path="/shop/{id}") */
    public function item(Item $item): Response
    {
        $form = $this->createForm(ItemType::class);

        return $this->render('client/item.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route (name="payment", path="/shop/{id}/payment")
     *
     * @throws OptimisticLockException|ORMException
     */
    public function payment(Item $item, Request $request, PaymentRequestBuilder $builder): Response
    {
        $request->request->set('uri', $request->getSchemeAndHttpHost());
        $request->request->set('locale', $request->getLocale());

        $form = $this->createForm(ItemType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request = $builder->buildResponse($form->getData(), $item);

            $redirectForm = $this->createForm(PaymentType::class);
            $redirectForm->submit($request);

            if($redirectForm->isSubmitted())

            return $this->render('client/payment.html.twig', [
                'form' => $redirectForm->createView(),
                'paymentType' => $form->getData()['payment']->getType()
            ]);
        }

        return $this->render('client/shop.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}