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
        try {
            // click $".selector"
        } catch (\Throwable $exception) {
            self::fail('{
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
                    "class": "' . addcslashes($exception::class, "'\\") . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), "'\\") . '"
                }
            }');
        }

        self::assertTrue(
            true,
            '{
                "statement-type": "assertion",
                "source": "$page.url is \"http:\/\/www.example.com\"",
                "identifier": "$page.url",
                "value": "\"http:\/\/www.example.com\"",
                "operator": "is",
                "index": 1
            }'
        );

        self::assertTrue(
            false,
            '{
                "statement-type": "assertion",
                "source": "$page.title is \"Foo\"",
                "identifier": "$page.title",
                "value": "\"Foo\"",
                "operator": "is",
                "index": 2
            }'
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
            '{
                "statement-type": "assertion",
                "source": "$page.title is \"Foo\"",
                "identifier": "$page.title",
                "value": "\"Foo\"",
                "operator": "is",
                "index": 0
            }'
        );
    }
}
