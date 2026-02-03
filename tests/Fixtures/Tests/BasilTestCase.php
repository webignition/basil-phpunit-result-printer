<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use PHPUnit\Framework\TestCase;
use webignition\BaseBasilTestCase\ClientManager;
use webignition\BaseBasilTestCase\Message\Factory;

class BasilTestCase extends TestCase
{
    protected static Factory $messageFactory;

    public static function setUpBeforeClass(): void
    {
        self::$messageFactory = Factory::createFactory();
    }

    public static function setClientManager(ClientManager $clientManager): void {}
}
