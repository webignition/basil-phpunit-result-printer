<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionInSetupBeforeClassTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        throw new \RuntimeException(
            'Exception thrown in setUpBeforeClass',
            456
        );
    }

    public function testStep1(): void
    {
        self::assertTrue(true);
    }
}
