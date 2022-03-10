<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class DatabaseTypeEnum extends Enum
{
    const MySQL = 0;
    const PostgreSQL = 1;
    const Oci8 = 2;
    const MongoDB = 3;
}