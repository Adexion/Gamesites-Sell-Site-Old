<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Action\Authentication;
use App\Controller\Admin\Action\Console;
use App\Controller\Admin\Action\PasswordManager;
use App\Repository\ServerRepository;
use App\Service\Connection\ExecuteServiceFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ControllerDashboard extends AbstractDashboard
{
    use PasswordManager;
    use Console;
    use Authentication;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(ServerRepository $serverRepository = null, ExecuteServiceFactory $executeServiceFactory = null): Response
    {
        $server = $serverRepository->findOneBy(['isDefault' => true]);
        $service = $server ? $executeServiceFactory->getExecutionService($server) : null;

        return $this->render('admin/dashboard.html.twig', [
            'response' => $this->serverResponse ?? '',
            'serverInfo' => $service ? $service->getInfo() : [],
        ]);
    }

    /**
     * @Route("/admin/changes", name="changes")
     */
    public function changes(): Response
    {
        return $this->render('admin/changes.html.twig');
    }

}
