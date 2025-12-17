<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;

readonly class FailedSubscriber implements FailedSubscriberInterface
{
    public function __construct(private Printer $printer) {}

    public function notify(Failed $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $this->printer->print($this->getFailedAssertion($event) . "\n");
    }

    private function getFailedAssertion(Failed $event): string
    {
        $test = $event->test();
        \assert($test instanceof TestMethod);

        $assertionFailureMessage = $event->throwable()->message();
        $assertionFailureMessageLines = explode("\n", $assertionFailureMessage);

        return $assertionFailureMessageLines[0];
    }
}
