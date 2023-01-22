<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Enums;

enum MetricType: string
{
    case INT = 'int';
    case FLOAT = 'float';
    case CURRENCY = 'currency';
}
