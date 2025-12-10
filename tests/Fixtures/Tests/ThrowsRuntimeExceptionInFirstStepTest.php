<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionInFirstStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::setBasilTestPath('/path/to/runtime-exception-on-first-step-test.yml');

        parent::setUpBeforeClass();
    }

    public function testStep1()
    {
        throw new \RuntimeException('Exception thrown in first step', 123);

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
