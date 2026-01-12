<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\NewStepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
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
        private NewStepFactory $newStepFactory,
        private GeneratorInterface $generator,
    ) {}

    public function notify(Finished $event): void
    {
        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testMetaData = $this->testMetaDataExtractor->extract($test);
        $statements = $testMetaData->statements;

        $assertionFailure = $this->state->getAssertionFailure();
        if ($assertionFailure instanceof AssertionFailure) {
            $statements->setFailedStatement($assertionFailure->statement);
        }

        $expectationFailure = $this->state->getExpectationFailure();
        if ($expectationFailure instanceof ExpectationFailure) {
            $statements->setFailedStatement($expectationFailure->assertion);
        }

        $testDataSet = null;
        $testData = $test->testData();
        if ($testData->hasDataFromDataProvider()) {
            $testDataSet = $this->testDataExtractor->extract($testData->dataFromDataProvider()->data());
        }

        $step = $this->newStepFactory->create(
            $testMetaData->stepName,
            $this->state,
            $statements,
            $assertionFailure,
            $expectationFailure,
            $testDataSet,
        );

        $this->printer->print($this->generator->generate($step->getData()));
    }
}
