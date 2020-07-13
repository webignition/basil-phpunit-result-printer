<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary;

interface AssertionFailureSummaryInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
