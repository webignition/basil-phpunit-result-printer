<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilPhpUnitResultPrinter\Model\Status;

class State
{
    private Status $status;
    private FailedAction $failedAction;
    private FailedAssertion $failedAssertion;

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

    public function setFailedAssertion(FailedAssertion $failedAssertion): void
    {
        $this->failedAssertion = $failedAssertion;
    }

    public function getFailedAssertion(): FailedAssertion
    {
        return $this->failedAssertion;
    }

    public function hasFailedAssertion(): bool
    {
        return isset($this->failedAssertion);
    }
}
