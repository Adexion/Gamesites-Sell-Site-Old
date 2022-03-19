<?php

namespace App\Service;

use App\Repository\TwitchRepository;
use App\Service\Connection\ExecuteServiceFactory;

class TwitchService
{
    private ExecuteServiceFactory $factory;

    public function __construct(ExecuteServiceFactory $executeServiceFactory)
    {
        $this->factory = $executeServiceFactory;
    }

    public function onlineStreamersList(TwitchRepository $repository): array
    {
        $online_list = [];
        $twitch = $repository->findAll();
        foreach ($twitch as $streamer) {
            $service = $this->factory->getExecutionService($streamer->getServer());
            if ($service->isPlayerLoggedIn($streamer->getMinecraftName())) {
                $online_list[] = $streamer->getChannelName();
            }
        }

        return $online_list;
    }
}