<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use PHPUnit\Framework\TestCase;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BaseBasilTestCase\ClientManager;

class BasilTestCase extends TestCase implements BasilTestCaseInterface
{
    public static function setClientManager(ClientManager $clientManager): void {}
}
