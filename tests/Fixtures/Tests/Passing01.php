<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class Passing01 extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        [
            'type' => 'action',
            'statement' => 'click identifier',
        ],
        [
            'type' => 'assertion',
            'statement' => 'assertion statement one for step one',
        ],
        [
            'type' => 'assertion',
            'statement' => 'assertion statement two for step one',
        ],
    ])]
    public function testStep1(): void
    {
        // click identifier
        // ...

        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement one for step one',
                'type' => 'assertion',
            ])
        );

        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement two for step one',
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
        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement for step two',
                'type' => 'assertion',
            ])
        );
    }
}
