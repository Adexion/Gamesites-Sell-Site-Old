<?php

namespace App\Controller\API;

use Psr\Log\LoggerInterface;
use App\Enum\OperatorResponseEnum;
use App\Enum\OperatorTypeEnum;
use App\Service\Payment\Response\OperatorFactory;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route(name="status", path="api/payment/status/{type}", methods={"POST"})
     */
    public function status(Request $request, OperatorFactory $factory, LoggerInterface $logger, string $type): Response
    {
        $type = OperatorTypeEnum::toArray()[OperatorResponseEnum::from($type)->getKey()];
        $request = empty($request->request->all())
            ? json_decode($request->getContent(), true)
            : $request->request->all();

        $logger->debug(json_encode($request), ['Operator input', $type]);
        
        try {
            $response = $factory->execute($type, $request);
        } catch (RuntimeException $e) {}

        return new Response($response ?? '', Response::HTTP_OK);
    }
}