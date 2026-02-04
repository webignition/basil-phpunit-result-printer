<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Enum;

enum StatementType: string
{
    case ACTION = 'action';
    case ASSERTION = 'assertion';
}
