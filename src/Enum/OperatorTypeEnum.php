<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class OperatorTypeEnum extends Enum
{
    public const HOT_PAY = 'HotPay';
    public const PAY_SAFE_CARD = 'PaySafeCard';
    public const DIRECT_BILLING = 'DirectBilling';
    public const CASH_BILL = 'CashBill';
    public const T_PAY = 'TPay';
    public const MICRO_SMS = 'MicroSMS';
    public const PAY_BY_LINK_PSC = 'PayByLinkPsc';
}