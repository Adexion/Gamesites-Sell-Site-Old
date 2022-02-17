<?php

namespace App\Service\Payment\Response\Operator;

interface OperatorInterface
{
    public function getResponse(array $request);

    public function validate(array $request);
}