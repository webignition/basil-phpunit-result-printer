<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use PHPUnit\Framework\TestCase;
use webignition\BaseBasilTestCase\ClientManager;

class BasilTestCase extends TestCase
{
    public static function setClientManager(ClientManager $clientManager): void {}
}
