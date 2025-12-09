<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber as FinishedSubscriberInterface;

class FinishedSubscriber implements FinishedSubscriberInterface
{
    public function notify(Finished $event): void {}
}
