<?php

namespace App\Controller\Admin\Action;

use Exception;
use App\Form\RConType;
use App\Service\Connection\ConsoleInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Connection\ExecuteServiceFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

trait Console
{
    /**
     * @Route("/admin/console", name="console")
     * @throws ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface
     */
    public function console(Request $request, ExecuteServiceFactory $factory): Response
    {
        $form = $this->createForm(RConType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = $factory->getExecutionService($form->getData()['server']);

            $response = [];
            try {
                $response[] = $service->execute($form->getData()['command']);
            } catch (Exception $e) {
                $response[] = $e->getMessage();
            }

            sleep(1);
            if ($service instanceof ConsoleInterface) {
                $response = array_merge($response, $service->getConsole());
            }
        }

        return $this->render('admin/console.html.twig', [
            'form' => $form->createView(),
            'response' => $response ?? [],
        ]);
    }
}