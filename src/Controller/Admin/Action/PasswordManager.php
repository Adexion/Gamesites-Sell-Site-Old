<?php

namespace App\Controller\Admin\Action;

use App\Form\AccessPasswordType;
use App\Form\PasswordManagerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PasswordManager as PasswordManagerEntity;

trait PasswordManager
{
    /**
     * @Route("/admin/passwd", name="admin_password_manager")
     */
    public function passwd(Request $request): Response
    {
        $form = $this->createForm(AccessPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('admin/passwd/list.html.twig', [
                'passwords' => $this->getUser()->getPasswordManager(),
                'key' => $form->getData()['password'],
            ]);
        }

        return $this->render('admin/passwd/access.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/passwd/{key}", name="admin_password_manager_add")
     */
    public function addPasswd(string $key, Request $request, EntityManagerInterface $entityManager): Response
    {
        $pm = new PasswordManagerEntity();
        $form = $this->createForm(PasswordManagerType::class, $pm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pm->setClient($this->getUser());
            $pm->setText($this->encrypt($key, $pm->getText()));

            $entityManager->persist($pm);
            $entityManager->flush();

            return $this->redirectToRoute('admin_password_manager');
        }

        return $this->render('admin/passwd/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function encrypt($key, $payload): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);

        return base64_encode($encrypted . '::' . $iv);
    }
}