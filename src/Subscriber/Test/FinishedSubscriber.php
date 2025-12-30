<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\StatusContainer;
use webignition\BasilPhpUnitResultPrinter\TestMetaDataExtractor;

readonly class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private StatusContainer $statusContainer,
        private TestMetaDataExtractor $testMetaDataExtractor,
    ) {}

    public function notify(Finished $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $this->printer->print('status: ' . $this->statusContainer);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testMetaData = $this->testMetaDataExtractor->extract($test);

        $this->printer->print($testMetaData->stepName . "\n");

        foreach ($testMetaData->statements as $statement) {
            $this->printer->print(json_encode($statement) . "\n");
        }
    }
}
