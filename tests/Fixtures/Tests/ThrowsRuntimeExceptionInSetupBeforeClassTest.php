<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

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
                'assertion' => 'assertion statement for step one',
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
        self::assertTrue(
            true,
            (string) json_encode([
                'assertion' => 'assertion statement for step two',
            ])
        );
    }
}
