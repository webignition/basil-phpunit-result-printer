<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class FailedAssertionStatement extends AbstractAssertionStatement
{
    public function __construct(
        string $source,
        private AssertionFailureSummaryInterface $failureSummary,
        array $transformations = []
    ) {
        $status = (string) new Status(Status::STATUS_FAILED);

        parent::__construct($source, $status, $transformations);
    }

    public function getData(): array
    {
        $data = parent::getData();
        $data['summary'] = $this->failureSummary->getData();

        return $data;
    }
}
