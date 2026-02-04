<?php

declare(strict_types=1);

namespace Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;

class FailedElementExistsAssertionAsThirdStatement extends BasilTestCase
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
            "source": "$\".selector\" exists",
            "index": 2,
            "identifier": "$\".selector\"",
            "operator": "exists"
        }',
    ])]
    public function testStep1(): void
    {
        // $".selector" exists <- click $".selector"
        // ...

        $this->assertTrue(
            true,
            '...'
        );

        // click $".selector"
        try {
            // click $".selector"
        } catch (\Throwable $exception) {
            $this->fail('{
                "statement": {
                    "statement-type": "action",
                    "source": "click $\".selector\"",
                    "index": 0,
                    "identifier": "$\".selector\"",
                    "type": "click",
                    "arguments": "$\".selector\""
                },
                "reason": "action-failed",
                "exception": {
                    "class": "' . addcslashes($exception::class, '"\\') . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), '"\\') . '"
                }
            }');
        }

        // $page.url is "http://www.example.com"

        self::assertTrue(
            true,
            '...'
        );

        $statement_2 = '{
            "statement-type": "assertion",
            "source": "$\".selector\" exists",
            "index": 2,
            "identifier": "$\".selector\"",
            "operator": "exists"
        }';

        $expected = true;
        $examined = false;

        self::assertTrue(
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_2, $expected, $examined),
        );
    }

    #[StepName('step two')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.title is \"Foo\"",
            "index": 0,
            "identifier": "$page.title",
            "value": "\"Foo\"",
            "operator": "is"
        }',
    ])]
    public function testStep2(): void
    {
        self::assertTrue(
            true,
            '...'
        );
    }
}
