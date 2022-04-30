<?php

namespace App\Controller\Admin\Action;

use Exception;
use App\Form\RConType;
use App\Entity\Server;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Connection\ExecuteServiceFactory;

trait Console
{
    /**
     * @Route("/admin/console", name="console")
     */
    public function console(Request $request, ExecuteServiceFactory $factory): Response
    {
        $form = $this->createForm(RConType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Server $server */
            $server = $form->getData()['server'];
            try {
                $response = $factory->getExecutionService($server)->execute($form->getData()['command']);
            } catch (Exception $e) {
                $response = $e->getMessage();
            }
        }

        return $this->render('admin/console.html.twig', [
            'form' => $form->createView(),
            'response' => $response ?? '',
        ]);
    }
}