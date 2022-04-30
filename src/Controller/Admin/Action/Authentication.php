<?php

namespace App\Controller\Admin\Action;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;

trait Authentication
{
    /**
     * @Route("/admin/user/2fa", name="user2fa")
     */
    public function twoFactoryAuthentication(Request $request, GoogleAuthenticatorInterface $googleAuthenticator, QrCodeGenerator $codeGenerator): Response
    {
        if ($request->request->get('generate')) {
            /** @var User $user */
            $user = $this->getUser();

            $secret = $googleAuthenticator->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $qrCodeContent = $codeGenerator->getGoogleAuthenticatorQrCode($user)->writeString();
        }

        if ($request->request->get('turnOff')) {
            /** @var User $user */
            $user = $this->getUser();
            $user->setGoogleAuthenticatorSecret(null);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('admin/2fa.html.twig', [
            'secret' => $secret ?? null,
            'qrCodeContent' => base64_encode($qrCodeContent ?? ''),
        ]);
    }

    /**
     * @Route("/admin/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class);

        return $this->render('admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }
}