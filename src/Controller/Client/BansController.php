<?php

namespace App\Controller\Client;


use App\Repository\BansRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class BansController extends AbstractController
{
    /**
     * @Route(name="bans", path="/bans")
     *
     * @throws Exception
     */
    public function bans(Request $request, BansRepository $repository): Response
    {
        $bans = array_map(function ($ban) {
            $date = new DateTime('@'. substr($ban['value'], 0, -3));
            $ban['value'] = $date->format('Y-m-d');

            return $ban;
        }, $repository->findRemote());

        return $this->render('client/bans.html.twig', [
            'bans' => $bans
        ]);
    }
}