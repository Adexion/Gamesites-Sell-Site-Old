<?php

namespace App\Controller\Client;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class ContactController extends AbstractController
{
    /**
     * @Route(name="contact", path="/contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Wiadomość wysłana');
        }

        return $this->render('client/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
