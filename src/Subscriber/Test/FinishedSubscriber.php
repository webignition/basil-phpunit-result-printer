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
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\DataSetExtractor;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\NameExtractor;
use webignition\BasilPhpUnitResultPrinter\StepDataExtractor\StatementCollectionExtractor;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private State $state,
        private NameExtractor $stepNameExtractor,
        private StatementCollectionExtractor $stepStatementCollectionExtractor,
        private DataSetExtractor $stepDataSetExtractor,
        private StepFactory $stepFactory,
        private GeneratorInterface $generator,
    ) {}

    public function notify(Finished $event): void
    {
        $test = $event->test();
        \assert($test instanceof TestMethod);

        $stepDataSet = null;
        $testData = $test->testData();
        if ($testData->hasDataFromDataProvider()) {
            $stepDataSet = $this->stepDataSetExtractor->extract($testData->dataFromDataProvider()->data());
        }

        $step = $this->stepFactory->create(
            $this->stepNameExtractor->extract($test),
            $this->state,
            $this->stepStatementCollectionExtractor->extract($test),
            $stepDataSet
        );

        $this->printer->print($this->generator->generate($step->getData()));
    }
}
