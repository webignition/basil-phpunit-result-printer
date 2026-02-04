<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber as PassedSubscriberInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;

readonly class PassedSubscriber implements PassedSubscriberInterface
{
    public function __construct(
        private State $state,
    ) {}

    public function notify(Passed $event): void
    {
        $this->state->setStatus(new Status(Status::STATUS_PASSED));
    }
}
