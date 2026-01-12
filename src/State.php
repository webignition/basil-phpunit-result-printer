<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilPhpUnitResultPrinter\Model\Status;

class State
{
    private Status $status;
    private FailedAction $failedAction;
    private ExpectationFailure $expectationFailure;
    private ?string $failureReason = null;

    /**
     * @var array<mixed>
     */
    private array $failureContext = [];

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

    public function setFailedAction(FailedAction $failedAction): void
    {
        $this->failedAction = $failedAction;
    }

    public function getFailedAction(): FailedAction
    {
        return $this->failedAction;
    }

    public function hasFailedAction(): bool
    {
        return isset($this->failedAction);
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

    public function setFailureReason(string $reason): void
    {
        $this->failureReason = $reason;
    }

    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    /**
     * @param array<mixed> $context
     */
    public function setFailureContext(array $context): void
    {
        $this->failureContext = $context;
    }

    /**
     * @return array<mixed>
     */
    public function getFailureContext(): array
    {
        return $this->failureContext;
    }
}
