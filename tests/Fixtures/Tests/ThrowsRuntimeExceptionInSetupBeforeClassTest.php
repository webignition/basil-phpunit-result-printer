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
        '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 0,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }',
    ])]
    public function testStep1(): void
    {
        self::assertTrue(
            true,
            '{
                "statement-type": "assertion",
                "source": "$page.url is \"http:\/\/www.example.com\"",
                "identifier": "$page.url",
                "value": "\"http:\/\/www.example.com\"",
                "operator": "is",
                "index": 0
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
