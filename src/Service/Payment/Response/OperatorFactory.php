<?php

namespace App\Service\Payment\Response;

use App\Repository\ItemHistoryRepository;
use App\Repository\ServerRepository;
use App\Service\Connection\ExecuteServiceFactory;
use RuntimeException;

class OperatorFactory
{
    private ItemHistoryRepository $historyRepository;
    private ServerRepository $serverRepository;
    private ExecuteServiceFactory $factory;

    public function __construct(ItemHistoryRepository $historyRepository, ServerRepository $serverRepository, ExecuteServiceFactory $factory)
    {
        $this->historyRepository = $historyRepository;
        $this->serverRepository = $serverRepository;
        $this->factory = $factory;
    }

    public function execute(string $type, array $request)
    {
        $className = 'App\Service\Payment\Response\Operator\\' . $type . 'Operator';

        if (!class_exists($className)) {
            throw new RuntimeException();
        }

        (new $className($this->historyRepository, $this->serverRepository, $this->factory))->getResponse($request);
    }
}