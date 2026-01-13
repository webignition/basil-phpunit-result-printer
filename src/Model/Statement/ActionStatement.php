<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;

class ActionStatement extends Statement
{
    public function __construct(string $source, string $status, array $transformations = [])
    {
        parent::__construct(StatementType::ACTION, $source, $status, $transformations);
    }
}
