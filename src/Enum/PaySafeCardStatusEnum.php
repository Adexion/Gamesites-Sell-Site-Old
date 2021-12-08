<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaySafeCardStatusEnum extends Enum
{
    const NEW = 0;
    const ACCEPTED = 1;
    const NOT_WORKING = -1;
}