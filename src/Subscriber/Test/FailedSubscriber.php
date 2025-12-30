<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\TestMetaDataExtractor;

readonly class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private TestMetaDataExtractor $testMetaDataExtractor,
    ) {}

    public function notify(Failed $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testMetaData = $this->testMetaDataExtractor->extract($test, $event->throwable());

        $this->printer->print($testMetaData->failedAssertion . "\n");
    }
}
