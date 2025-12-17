<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

class ThrowsRuntimeExceptionInFirstStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public function testStep1()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'step' => 'step one',
                'assertion' => 'assertion statement for step one',
            ])
        );

        throw new \RuntimeException('Exception thrown in first step', 123);
    }

    public function testStep2()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'step' => 'step two',
                'assertion' => 'assertion statement for step two',
            ])
        );
    }
}
