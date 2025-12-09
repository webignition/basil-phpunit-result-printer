<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber as FailedSubscriberInterface;

class FailedSubscriber implements FailedSubscriberInterface
{
    public function notify(Failed $event): void {}
}
