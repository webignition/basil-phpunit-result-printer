<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilPhpUnitResultPrinter\FailedAction;
use webignition\BasilPhpUnitResultPrinter\FailedActionExtractor;
use webignition\BasilPhpUnitResultPrinter\FailedAssertionExtractor;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StatementMessageParser;

class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(
        private readonly Printer $printer,
        private State $state,
        private readonly StatementMessageParser $statementMessageParser,
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

        $parsedStatementMessage = $this->statementMessageParser->parse($event->throwable()->message());
        $failedAction = $this->failedActionExtractor->extract($parsedStatementMessage['data']);
        if ($failedAction instanceof FailedAction) {
            $this->state->setFailedAction($failedAction);
        }

        $failedAssertion = $this->failedAssertionExtractor->extract($parsedStatementMessage['data']);
        if ($failedAssertion instanceof AssertionInterface) {
            $this->state->setFailedAssertion($failedAssertion);

            $expected = $parsedStatementMessage['data']['expected'];
            $examined = $parsedStatementMessage['data']['examined'];

            if (is_string($expected) || is_bool($expected)) {
                $this->state->setExpectedValue($expected);
            }

            if (is_string($examined) || is_bool($examined)) {
                $this->state->setActualValue($examined);
            }
        }
    }
}
