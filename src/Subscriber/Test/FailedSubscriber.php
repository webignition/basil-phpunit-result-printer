<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

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
    }
}
