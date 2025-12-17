<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\StepName;

class ThrowsRuntimeExceptionInFirstStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    public function testStep1()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'assertion' => 'assertion statement for step one',
            ])
        );

        throw new \RuntimeException('Exception thrown in first step', 123);
    }

    #[StepName('step two')]
    public function testStep2()
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'assertion' => 'assertion statement for step two',
            ])
        );
    }
}
