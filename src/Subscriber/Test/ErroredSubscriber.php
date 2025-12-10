<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber as ErroredSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;

readonly class ErroredSubscriber implements ErroredSubscriberInterface
{
    public function __construct(private Printer $printer) {}

    public function notify(Errored $event): void
    {
        $this->printer->print($event::class);
        $this->printer->print("\n");
    }
}
