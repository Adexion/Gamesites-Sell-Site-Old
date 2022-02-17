<?php

namespace App\Controller\API;

use App\Enum\OperatorResponseEnum;
use App\Enum\OperatorTypeEnum;
use App\Service\Payment\Response\OperatorFactory;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route(name="status", path="api/payment/status/{type}", methods={"POST"})
     */
    public function status(Request $request, OperatorFactory $factory, string $type): Response
    {
        $type = OperatorTypeEnum::toArray()[OperatorResponseEnum::from($type)->getKey()];

        try {
            $request = json_decode($request->getContent(), true);

            $factory->execute($type, $request);
        } catch (RuntimeException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse();
    }
}