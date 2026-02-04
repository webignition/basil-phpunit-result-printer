<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BaseBasilTestCase\Enum\StatementStage;

class Passing01 extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "action",
            "source": "click $\".selector\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "type": "click",
            "arguments": "$\".selector\""            
        }',
        '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 1,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }',
        '{
            "statement-type": "assertion",
            "source": "$page.title is \"Foo\"",
            "index": 2,
            "identifier": "$page.title",
            "value": "\"Foo\"",
            "operator": "is"
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "statement-type": "action",
            "source": "click $\".selector\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "type": "click",
            "arguments": "$\".selector\""            
        }';

        $statement_1 = '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 1,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }';

        $statement_2 = '{
            "statement-type": "assertion",
            "source": "$page.title is \"Foo\"",
            "index": 2,
            "identifier": "$page.title",
            "value": "\"Foo\"",
            "operator": "is"
        }';

        try {
            // click $".selector"
            // ...
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

        $expected = 'Foo';
        $examined = 'Foo';

        self::assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_2, $expected, $examined),
        );
    }

    #[StepName('step two')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 0,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }',
    ])]
    public function testStep2(): void
    {
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is",
            "index": 0
        }';

        $expected = 'http:/www.example.com';
        $examined = 'http:/www.example.com';

        self::assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
