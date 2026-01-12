<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\TestDataExtractor;
use webignition\BasilPhpUnitResultPrinter\TestMetaDataExtractor;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private State $state,
        private TestMetaDataExtractor $testMetaDataExtractor,
        private TestDataExtractor $testDataExtractor,
    ) {}

    public function notify(Finished $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $this->printer->print('status: ' . $this->state->getStatus());
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testData = $test->testData();
        if ($testData->hasDataFromDataProvider()) {
            $testDataSet = $this->testDataExtractor->extract($testData->dataFromDataProvider()->data());
            $this->printer->print('provided data:');
            $this->printer->print("\n");
            $this->printer->print((string) json_encode($testDataSet));
            $this->printer->print("\n");
        }

        $testMetaData = $this->testMetaDataExtractor->extract($test);
        $this->printer->print($testMetaData->stepName . "\n");

        foreach ($testMetaData->statements as $statement) {
            $this->printer->print(json_encode($statement, JSON_PRETTY_PRINT) . "\n");
        }

        $assertionFailure = $this->state->getAssertionFailure();
        if ($assertionFailure instanceof AssertionFailure) {
            $this->printer->print('assertion failure statement: ' . $assertionFailure->statement . "\n");

            $this->printer->print('reason: "' . $assertionFailure->reason . "\"\n");

            $exception = $assertionFailure->exception;
            $this->printer->print('exception class: "' . $exception->class . "\"\n");
            $this->printer->print('exception code: "' . $exception->code . "\"\n");
            $this->printer->print('exception message: "' . $exception->message . "\"\n");

            $this->printer->print('context: "' . json_encode($assertionFailure->context) . "\"\n");
        }

        $expectationFailure = $this->state->getExpectationFailure();
        if ($expectationFailure instanceof ExpectationFailure) {
            $this->printer->print('failed assertion: ' . $expectationFailure->assertion . "\n");

            $expected = $expectationFailure->expected;
            if (is_bool($expected)) {
                $expected = $expected ? 'true' : 'false';
            }

            $this->printer->print('expected: "' . $expected . "\"\n");

            $actual = $expectationFailure->examined;
            if (is_bool($actual)) {
                $actual = $actual ? 'true' : 'false';
            }

            $this->printer->print('actual: "' . $actual . "\"\n");
        }
    }
}
