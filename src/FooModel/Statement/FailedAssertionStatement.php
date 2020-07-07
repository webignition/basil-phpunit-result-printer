<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;

class FailedAssertionStatement extends AbstractAssertionStatement
{
    private AssertionFailureSummaryInterface $failureSummary;

    public function __construct(
        string $source,
        AssertionFailureSummaryInterface $failureSummary,
        array $transformations = []
    ) {
        $status = (string) new Status(Status::STATUS_FAILED);

        parent::__construct($source, $status, $transformations);

        $this->failureSummary = $failureSummary;
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['summary'] = $this->failureSummary->getData();

        return $data;
    }
}
