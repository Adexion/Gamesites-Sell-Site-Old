<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class TemplateEnum extends Enum
{
    const GUILD_DARK = 'client/guild/dark/';
    const GUILD_WHITE = 'client/guild/white/';
    const MULTI_DARK = 'client/multi/dark/';
    const MULTI_WHITE = 'client/multi/white/';
    const SIMPLE_DARK = 'client/simple/dark/';
    const NONE = '';
}