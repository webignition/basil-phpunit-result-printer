<?php

declare(strict_types=1);

namespace Fixtures\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;

class PassingWithDataProvider extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        [
            'type' => 'action',
            'statement' => 'set $".selector" to $data.value',
        ],
        [
            'type' => 'assertion',
            'statement' => 'assertion statement one for step one',
        ],
    ])]
    #[DataProvider('StepOneDataProvider')]
    public function testStep1(int $foo, string $bar, bool $fooBar): void
    {
        try {
            // set $".selector" to $data.value
        } catch (\Throwable $exception) {
            self::fail('{
                "statement": {
                    "statement": "set $\".selector\" to $data.value",
                    "type": "action"
                },
                "reason": "action-failed",
                "exception": {
                    "class": ' . addcslashes($exception::class, "'\\") . ',
                    "code": ' . $exception->getCode() . ',
                    "message": ' . addcslashes($exception->getMessage(), "'\\") . '
                }
            }');
        }

        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement one for step one',
                'type' => 'assertion',
            ])
        );
    }

    public static function StepOneDataProvider(): array
    {
        return [
            'value is one' => [
                'foo' => 1,
                'bar' => 'two',
                'fooBar' => true,
            ],
            'value is seven' => [
                'foo' => 7,
                'bar' => 'eight',
                'fooBar' => true,
            ],
            'value is nine' => [
                'foo' => 9,
                'bar' => 'ten',
                'fooBar' => false,
            ],
        ];
    }
}
