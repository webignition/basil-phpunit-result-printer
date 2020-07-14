<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

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
