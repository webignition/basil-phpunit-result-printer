<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure\AssertionFailureFactory;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure\ExpectationFailureFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StatementMessageParser;

readonly class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(
        private State $state,
        private StatementMessageParser $statementMessageParser,
        private AssertionFailureFactory $assertionFailureFactory,
        private ExpectationFailureFactory $expectationFailureFactory,
    ) {}

    public function notify(Failed $event): void
    {
        $this->state->setStatus(new Status(Status::STATUS_FAILED));

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $parsedStatementMessage = $this->statementMessageParser->parse($event->throwable()->message());

        $assertionFailure = $this->assertionFailureFactory->create($parsedStatementMessage['data']);
        if ($assertionFailure instanceof AssertionFailure) {
            $this->state->setAssertionFailure($assertionFailure);
        }

        $expectationFailure = $this->expectationFailureFactory->create($parsedStatementMessage['data']);
        if ($expectationFailure instanceof ExpectationFailure) {
            $this->state->setExpectationFailure($expectationFailure);
        }
    }
}
