<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

class State
{
    private Status $status;
    private FailedAction $failedAction;
    private AssertionInterface $failedAssertion;

    private ?string $expected = null;
    private ?string $actual = null;

    private bool $hasFailedAssertTrueAssertion = false;
    private bool $hasFailedAssertFalseAssertion = false;

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

    public function setFailedAssertion(AssertionInterface $assertion): void
    {
        $this->failedAssertion = $assertion;
    }

    public function getFailedAssertion(): AssertionInterface
    {
        return $this->failedAssertion;
    }

    public function hasFailedAssertion(): bool
    {
        return isset($this->failedAssertion);
    }

    public function setExpectedValue(string $value): void
    {
        $this->expected = $value;
    }

    public function getExpectedValue(): ?string
    {
        return $this->expected;
    }

    public function hasExpectedValue(): bool
    {
        return null !== $this->expected;
    }

    public function setActualValue(string $value): void
    {
        $this->actual = $value;
    }

    public function getActualValue(): ?string
    {
        return $this->actual;
    }

    public function hasActualValue(): bool
    {
        return null !== $this->actual;
    }

    public function hasFailedAssertTrueAssertion(): bool
    {
        return $this->hasFailedAssertTrueAssertion;
    }

    public function setHasFailedAssertTrueAssertion(): void
    {
        $this->hasFailedAssertTrueAssertion = true;
    }

    public function hasFailedAssertFalseAssertion(): bool
    {
        return $this->hasFailedAssertFalseAssertion;
    }

    public function setHasFailedAssertFalseAssertion(): void
    {
        $this->hasFailedAssertFalseAssertion = true;
    }
}
