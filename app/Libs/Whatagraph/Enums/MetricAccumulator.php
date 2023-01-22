<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Enums;

enum MetricAccumulator: string
{
    case SUM = 'sum';
    case AVERAGE = 'average';
    case LAST = 'last';
}
