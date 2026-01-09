<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\BeforeFirstTestMethodErrored;
use PHPUnit\Event\Test\BeforeFirstTestMethodErroredSubscriber as BeforeFirstTestMethodErroredSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Model\Status;

readonly class BeforeFirstTestMethodErroredSubscriber implements BeforeFirstTestMethodErroredSubscriberInterface
{
    public function __construct(
        private Printer $printer,
    ) {}

    public function notify(BeforeFirstTestMethodErrored $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");

        $this->printer->print('status: ' . new Status(Status::STATUS_TERMINATED));
        $this->printer->print("\n");

        $this->printer->print('throwable: "' . trim($event->throwable()->description()) . "\"\n");
    }
}
