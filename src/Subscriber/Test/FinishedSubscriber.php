<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\StepNameExtractor;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\StepStatementCollectionExtractor;
use webignition\BasilPhpUnitResultPrinter\TestDataExtractor;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private State $state,
        private StepNameExtractor $stepNameExtractor,
        private StepStatementCollectionExtractor $statementCollectionExtractor,
        private TestDataExtractor $testDataExtractor,
        private StepFactory $stepFactory,
        private GeneratorInterface $generator,
    ) {}

    public function notify(Finished $event): void
    {
        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testDataSet = null;
        $testData = $test->testData();
        if ($testData->hasDataFromDataProvider()) {
            $testDataSet = $this->testDataExtractor->extract($testData->dataFromDataProvider()->data());
        }

        $step = $this->stepFactory->create(
            $this->stepNameExtractor->extract($test),
            $this->state,
            $this->statementCollectionExtractor->extract($test),
            $testDataSet
        );

        $this->printer->print($this->generator->generate($step->getData()));
    }
}
