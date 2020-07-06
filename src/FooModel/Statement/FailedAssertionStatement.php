<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\AssertionFailureSummaryInterface;

class FailedAssertionStatement extends AbstractAssertionStatement
{
    private const STATUS = 'failed';

    private AssertionFailureSummaryInterface $failureSummary;

    public function __construct(
        string $source,
        AssertionFailureSummaryInterface $failureSummary,
        array $transformations = []
    ) {
        parent::__construct($source, self::STATUS, $transformations);

        $this->failureSummary = $failureSummary;
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['summary'] = $this->failureSummary->getData();

        return $data;
    }
}
