<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilPhpUnitResultPrinter\Model\Status;

class State
{
    private Status $status;
    private AssertionFailure $assertionFailure;
    private ExpectationFailure $expectationFailure;

    public function __construct()
    {
        $this->status = new Status(Status::STATUS_UNKNOWN);
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function setAssertionFailure(AssertionFailure $failedAction): void
    {
        $this->assertionFailure = $failedAction;
    }

    public function getAssertionFailure(): AssertionFailure
    {
        return $this->assertionFailure;
    }

    public function hasAssertionFailure(): bool
    {
        return isset($this->assertionFailure);
    }

    public function setExpectationFailure(ExpectationFailure $assertion): void
    {
        $this->expectationFailure = $assertion;
    }

    public function getExpectationFailure(): ExpectationFailure
    {
        return $this->expectationFailure;
    }

    public function hasExpectationFailure(): bool
    {
        return isset($this->expectationFailure);
    }
}
