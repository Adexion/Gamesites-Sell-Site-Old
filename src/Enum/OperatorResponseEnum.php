<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class OperatorResponseEnum extends Enum
{
    public const HOT_PAY = '1';
    public const PAY_SAFE_CARD = '2';
    public const DIRECT_BILLING = '3';
    public const CASH_BILL = '4';
    public const T_PAY = '5';
    public const MICRO_SMS = '6';
    public const PAY_BY_LINK_PSC = '7';
}