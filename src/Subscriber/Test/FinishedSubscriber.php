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
use webignition\BasilPhpUnitResultPrinter\StepInspector;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private State $state,
        private StepInspector $stepInspector,
        private StepFactory $stepFactory,
        private GeneratorInterface $generator,
    ) {}

    public function notify(Finished $event): void
    {
        $test = $event->test();
        \assert($test instanceof TestMethod);

        $this->stepInspector->setTestMethod($test);

        $step = $this->stepFactory->create(
            $this->stepInspector->getName(),
            $this->state,
            $this->stepInspector->getStatements(),
            $this->stepInspector->getDataSet()
        );

        $this->printer->print($this->generator->generate($step->getData()));
    }
}
