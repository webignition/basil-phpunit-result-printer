<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\TestDataExtractor;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private TestDataExtractor $testDataExtractor,
    ) {}

    public function notify(Finished $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testData = $this->testDataExtractor->extract($test);

        $this->printer->print($testData->stepName . "\n");

        foreach ($testData->statements as $statement) {
            $this->printer->print(json_encode($statement) . "\n");
        }
    }
}
