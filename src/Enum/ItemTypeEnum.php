<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class ItemTypeEnum extends Enum
{
    const ITEM = 'item';
    const PREPAID = 'prepaid';
    const USEABLE = 'useable';
}