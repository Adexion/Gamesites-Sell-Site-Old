<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class OperatorTypeEnum extends Enum
{
    public const HOT_PAY = 'HotPay';
    public const CASH_BILL = 'CashBill';
    public const T_PAY = 'TPay';
    public const DIRECT_BILLING = 'DirectBilling';
    public const PAY_SAFE_CARD = 'PaySafeCard';
    public const MICRO_SMS = 'MicroSMS';
}