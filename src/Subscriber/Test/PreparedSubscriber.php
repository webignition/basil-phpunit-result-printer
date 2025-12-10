<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber as PreparedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;

readonly class PreparedSubscriber implements PreparedSubscriberInterface
{
    public function __construct(private Printer $printer) {}

    public function notify(Prepared $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");
    }
}
