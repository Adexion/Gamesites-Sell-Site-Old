<?php

namespace App\Service\Payment\Response\Operator;

interface OperatorInterface
{
    public function getResponse(array $request): ?string;

    public function validate(array $request);
}