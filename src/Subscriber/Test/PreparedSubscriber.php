<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Subscriber\Test;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber as PreparedSubscriberInterface;

class PreparedSubscriber implements PreparedSubscriberInterface
{
    public function notify(Prepared $event): void {}
}
