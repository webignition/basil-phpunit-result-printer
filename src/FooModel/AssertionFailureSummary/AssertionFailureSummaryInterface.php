<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary;

interface AssertionFailureSummaryInterface
{
    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
