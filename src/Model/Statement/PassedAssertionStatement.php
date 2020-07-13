<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\Status;

class PassedAssertionStatement extends AbstractAssertionStatement
{
    public function __construct(string $source, array $transformations = [])
    {
        $status = (string) new Status(Status::STATUS_PASSED);

        parent::__construct($source, $status, $transformations);
    }
}
