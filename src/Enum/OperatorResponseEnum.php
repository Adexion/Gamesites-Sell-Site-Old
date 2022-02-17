<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class OperatorResponseEnum extends Enum
{
    public const HOT_PAY = '1';
    public const PAY_SAFE_CARD = '2';
    public const DIRECT_BILLING = '3';
    public const CASH_BILL = '3';
}