<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use PHPUnit\Runner\BaseTestRunner;

class Status
{
    public const SUCCESS = BaseTestRunner::STATUS_PASSED;
    public const FAILURE = BaseTestRunner::STATUS_FAILURE;
}
