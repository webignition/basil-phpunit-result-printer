<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber as PassedSubscriberInterface;

class PassedSubscriber implements PassedSubscriberInterface
{
    public function notify(Passed $event): void
    {
        // TODO: Implement notify() method.
    }
}
