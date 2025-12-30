<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\StatusContainer;
use webignition\BasilPhpUnitResultPrinter\TestMetaDataExtractor;

class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(
        private readonly Printer $printer,
        private StatusContainer $statusContainer,
        private readonly TestMetaDataExtractor $testMetaDataExtractor,
    ) {}

    public function notify(Failed $event): void
    {
        $this->statusContainer->setStatus(new Status(Status::STATUS_FAILED));

        $this->printer->print($event::class);
        $this->printer->print("\n");

        $test = $event->test();
        \assert($test instanceof TestMethod);

        $testMetaData = $this->testMetaDataExtractor->extract($test, $event->throwable());

        $this->printer->print($testMetaData->failedAssertion . "\n");
    }
}
