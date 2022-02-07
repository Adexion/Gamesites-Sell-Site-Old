<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaymentStatusEnum extends Enum
{
    const CREATED = 0;
    const ACCEPTED = 1;
    const UNACCEPTED = 2;
    const TIME_OUT = 3;
    const CANCELED = 4;

    const REALIZED = 10;
    const NOT_EXISTED = 11;
    const NOT_ON_SERVER = 12;
}