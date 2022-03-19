<?php

namespace App\Controller\Client;

use App\Repository\TwitchRepository;
use App\Service\TwitchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TwitchController extends AbstractController
{
    /**
     *  @Route(name="twitch_api_list", path="/api/streamer/list")
     */
    public function apiList(TwitchRepository $repository, TwitchService $service): JsonResponse
    {
        return new JsonResponse($service->onlineStreamersList($repository));
    }
}