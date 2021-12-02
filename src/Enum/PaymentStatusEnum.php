<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaymentStatusEnum extends Enum
{
    const CREATED = 0;
    const ACCEPTED = 10;
    const UNACCEPTED = 20;
    const TIME_OUT = 30;
    const CANCELED = 40;

    const REALIZED = 100;
    const NOT_EXISTED = 110;
    const NOT_ON_SERVER = 120;
}