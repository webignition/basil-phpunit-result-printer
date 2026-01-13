<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;

abstract class AbstractAssertionStatement extends Statement
{
    public function __construct(string $source, string $status, array $transformations = [])
    {
        parent::__construct(StatementType::ASSERTION, $source, $status, $transformations);
    }
}
