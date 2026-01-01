<?php

declare(strict_types=1);

namespace Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;

class FailingAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        [
            'type' => 'action',
            'statement' => 'click $".selector"',
        ],
        [
            'type' => 'assertion',
            'statement' => '$page.url is "https://www.example.com"',
        ],
        [
            'type' => 'assertion',
            'statement' => '$page.title is "Foo"',
        ],
    ])]
    public function testStep1(): void
    {
        try {
            // click $".selector"
        } catch (\Throwable $exception) {
            self::fail('{
                "statement": {
                    "statement": "click $\".selector\"",
                    "type": "action"
                },
                "reason": "action-failed",
                "exception": {
                    "class": "' . addcslashes($exception::class, "'\\") . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), "'\\") . '"
                }
            }');
        }

        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => '$page.url is "https://www.example.com"',
                'type' => 'assertion',
            ])
        );

        self::assertTrue(
            false,
            (string) json_encode([
                'statement' => '$page.title is "Foo"',
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
