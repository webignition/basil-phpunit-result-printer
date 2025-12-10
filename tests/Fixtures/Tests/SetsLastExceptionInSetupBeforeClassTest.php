<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class SetsLastExceptionInSetupBeforeClassTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::setBasilTestPath('/path/to/set-last-exception-in-setup-before-class.yml');
        parent::setUpBeforeClass();

        self::$lastException = new \RuntimeException(
            'Exception thrown in setUpBeforeClass',
            456
        );
    }

    public function testStep1()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'step' => 'step one',
                'assertion' => 'assertion statement for step one'
            ])
        );
    }

    public function testStep2()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'step' => 'step two',
                'assertion' => 'assertion statement for step two'
            ])
        );
    }
}
