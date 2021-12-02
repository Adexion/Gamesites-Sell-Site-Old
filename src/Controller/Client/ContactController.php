<?php

namespace App\Controller\Client;

use App\Form\ContactType;
use App\Service\Mail\SenderService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class ContactController extends AbstractController
{
    /**
     * @Route(name="contact", path="/contact")
     *
     * @throws Exception| TransportExceptionInterface
     */
    public function contact(Request $request, SenderService $service): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->sendEmailBySchema('contact', $form->getData());

            $this->addFlash('success', 'Wiadomość wysłana');
        }

        return $this->render('client/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
