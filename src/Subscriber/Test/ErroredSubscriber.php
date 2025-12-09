<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber as ErroredSubscriberInterface;

class ErroredSubscriber implements ErroredSubscriberInterface
{
    public function notify(Errored $event): void {}
}
