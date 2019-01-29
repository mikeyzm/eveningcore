<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ConvertStatus extends Enum
{
    const Pending = 0;
    const Converting = 1;
    const Converted = 2;
    const Failed = 3;
    const Expired = 4;
}
