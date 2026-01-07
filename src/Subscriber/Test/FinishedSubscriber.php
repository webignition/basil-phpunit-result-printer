<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
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

        if ($this->state->hasFailedAction()) {
            $this->printer->print('failed action: ' . $this->state->getFailedAction()->action . "\n");
        }

        if ($this->state->hasFailedAssertion()) {
            $this->printer->print('failed assertion: ' . $this->state->getFailedAssertion() . "\n");

            if ($this->state->hasExpectedValue()) {
                $this->printer->print('expected: "' . $this->state->getExpectedValue() . "\"\n");
            }

            if ($this->state->hasActualValue()) {
                $this->printer->print('actual: "' . $this->state->getActualValue() . "\"\n");
            }

            if ($this->state->hasFailedAssertTrueAssertion()) {
                $this->printer->print('expected: true' . "\n");
                $this->printer->print('actual: false' . "\n");
            }

            if ($this->state->hasFailedAssertFalseAssertion()) {
                $this->printer->print('expected: false' . "\n");
                $this->printer->print('actual: true' . "\n");
            }
        }
    }
}
