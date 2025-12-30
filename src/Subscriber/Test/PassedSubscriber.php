<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber as PassedSubscriberInterface;
use PHPUnit\TextUI\Output\Printer;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\StatusContainer;

class PassedSubscriber implements PassedSubscriberInterface
{
    public function __construct(
        private Printer $printer,
        private StatusContainer $statusContainer,
    ) {}

    public function notify(Passed $event): void
    {
        $this->statusContainer->setStatus(new Status(Status::STATUS_PASSED));

        $this->printer->print($event::class);
        $this->printer->print("\n");
    }
}
