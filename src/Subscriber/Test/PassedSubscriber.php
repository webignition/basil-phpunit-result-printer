<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber as PassedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;

readonly class PassedSubscriber implements PassedSubscriberInterface
{
    public function __construct(private Printer $printer) {}

    public function notify(Passed $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");
    }
}
