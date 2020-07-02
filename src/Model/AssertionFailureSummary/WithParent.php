<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\Literal;

class WithParent extends Literal
{
    public function __construct()
    {
        parent::__construct('with parent:');
    }
}
