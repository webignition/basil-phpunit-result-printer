<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\BeforeFirstTestMethodErrored;
use PHPUnit\Event\Test\BeforeFirstTestMethodErroredSubscriber as BeforeFirstTestMethodErroredSubscriberInterface;

class BeforeFirstTestMethodErroredSubscriber implements BeforeFirstTestMethodErroredSubscriberInterface
{
    public function notify(BeforeFirstTestMethodErrored $event): void {}
}
