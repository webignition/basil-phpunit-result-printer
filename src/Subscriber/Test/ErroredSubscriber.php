<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber as ErroredSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\StatusContainer;

class ErroredSubscriber implements ErroredSubscriberInterface
{
    public function __construct(
        private readonly Printer $printer,
        private StatusContainer $statusContainer,
    ) {}

    public function notify(Errored $event): void
    {
        $this->statusContainer->setStatus(new Status(Status::STATUS_TERMINATED));

        $this->printer->print($event::class);
        $this->printer->print("\n");

        $this->printer->print($event->throwable()->description());
    }
}
