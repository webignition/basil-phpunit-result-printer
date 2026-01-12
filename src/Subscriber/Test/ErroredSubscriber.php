<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber as ErroredSubscriberInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;

readonly class ErroredSubscriber implements ErroredSubscriberInterface
{
    public function __construct(
        private State $state,
    ) {}

    public function notify(Errored $event): void
    {
        $this->state->setStatus(new Status(Status::STATUS_TERMINATED));
    }
}
