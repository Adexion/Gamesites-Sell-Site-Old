<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaymentTypeEnum extends Enum
{
    public const HOT_PAY = 'platnosc';
    public const PAY_SAFE_CARD = 'psc';
    public const DIRECT_BILLING = 'directbilling';
}