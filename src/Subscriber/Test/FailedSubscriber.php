<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\FailedAction;
use webignition\BasilPhpUnitResultPrinter\FailedActionExtractor;
use webignition\BasilPhpUnitResultPrinter\FailedAssertion;
use webignition\BasilPhpUnitResultPrinter\FailedAssertionExtractor;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;

class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(
        private readonly Printer $printer,
        private State $state,
        private readonly FailedActionExtractor $failedActionExtractor,
        private readonly FailedAssertionExtractor $failedAssertionExtractor,
    ) {}

    public function notify(Failed $event): void
    {
        $this->state->setStatus(new Status(Status::STATUS_FAILED));

        $this->printer->print($event::class);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $failedAction = $this->failedActionExtractor->extract($event->throwable());
        if ($failedAction instanceof FailedAction) {
            $this->state->setFailedAction($failedAction);
        }

        $failedAssertion = $this->failedAssertionExtractor->extract($event->throwable());
        if ($failedAssertion instanceof FailedAssertion) {
            $this->state->setFailedAssertion($failedAssertion);
        }
    }
}
