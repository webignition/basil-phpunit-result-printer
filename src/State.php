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
    private bool|string|null $expected = null;
    private bool|string|null $actual = null;
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

    public function setExpectedValue(bool|string $value): void
    {
        $this->expected = $value;
    }

    public function getExpectedValue(): bool|string|null
    {
        return $this->expected;
    }

    public function hasExpectedValue(): bool
    {
        return null !== $this->expected;
    }

    public function setActualValue(bool|string $value): void
    {
        $this->actual = $value;
    }

    public function getActualValue(): bool|string|null
    {
        return $this->actual;
    }

    public function hasActualValue(): bool
    {
        return null !== $this->actual;
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
