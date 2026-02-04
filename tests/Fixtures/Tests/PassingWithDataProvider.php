<?php

declare(strict_types=1);

namespace Fixtures\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BaseBasilTestCase\Enum\StatementStage;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;

class PassingWithDataProvider extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "action",
            "source": "set $\".selector\" to $data.value",
            "index": 0,
            "identifier": "$\".selector\"",
            "type": "set",
            "arguments": "$data.value"
        }',
        '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 1,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }',
    ])]
    #[DataProvider('StepOneDataProvider')]
    public function testStep1(int $foo, string $bar, bool $fooBar): void
    {
        $statement_0 = '{
            "statement-type": "action",
            "source": "set $\".selector\" to $data.value",
            "index": 0,
            "identifier": "$\".selector\"",
            "type": "set",
            "arguments": "$data.value"
        }';

        $statement_1 = '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 1,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }';

        try {
            // set $".selector" to $data.value
        } catch (\Throwable $exception) {
            self::fail(
                (string) self::$messageFactory->createFailureMessage(
                    $statement_0,
                    $exception,
                    StatementStage::EXECUTE,
                )
            );
        }

        $expected = 'http:/www.example.com';
        $examined = 'http:/www.example.com';

        self::assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_1, $expected, $examined),
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
