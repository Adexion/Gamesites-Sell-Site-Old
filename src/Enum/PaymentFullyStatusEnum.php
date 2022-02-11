<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaymentFullyStatusEnum extends PaymentStatusEnum
{
    const FAILURE = 'FAILURE';
    const PENDING = 'PENDING';
}