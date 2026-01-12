<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailureExtractor;
use webignition\BasilPhpUnitResultPrinter\FailedAction;
use webignition\BasilPhpUnitResultPrinter\FailedActionExtractor;
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
        private readonly ExpectationFailureExtractor $expectationFailureExtractor,
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

        $expectationFailure = $this->expectationFailureExtractor->extract($parsedStatementMessage['data']);
        if ($expectationFailure instanceof ExpectationFailure) {
            $this->state->setExpectationFailure($expectationFailure);
            //            $reason = $parsedStatementMessage['data']['reason'];
            //            if (is_string($reason)) {
            //                $this->state->setFailureReason($reason);
            //            }
            //
            //            $context = $parsedStatementMessage['data']['context'];
            //            if (is_array($context)) {
            //                $this->state->setFailureContext($context);
            //            }
        }
    }
}
