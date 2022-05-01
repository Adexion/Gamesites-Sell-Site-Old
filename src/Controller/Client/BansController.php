<?php

namespace App\Controller\Client;

use App\Repository\BansRepository;
use App\Service\BansMapper;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BansController extends AbstractRenderController
{
    /**
     * @Route(name="bans", path="/bans")
     *
     * @throws Exception|\Doctrine\DBAL\Driver\Exception
     */
    public function bans(BansRepository $repository, BansMapper $bansMapper): Response
    {
        return $this->renderTheme('bans.html.twig', [
            'bans' => $bansMapper->map($repository->findRemote()),
        ]);
    }
}