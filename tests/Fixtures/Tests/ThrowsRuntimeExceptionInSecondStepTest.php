<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class ThrowsRuntimeExceptionInSecondStepTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        [
            'type' => 'assertion',
            'statement' => 'assertion statement for step one',
        ],
    ])]
    public function testStep1(): void
    {
        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement for step one',
                'type' => 'assertion',
            ])
        );
    }

    #[StepName('step two')]
    #[Statements([
        [
            'type' => 'assertion',
            'statement' => 'assertion statement for step two',
        ],
    ])]
    public function testStep2(): void
    {
        throw new \RuntimeException('Exception thrown in second step', 123);
        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement for step two',
                'type' => 'assertion',
            ])
        );
    }
}
