<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedLiteralIsRegexpAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.title matches \"not a regexp\"",
            "index": 0,
            "identifier": "$page.title",
            "value": "\"not a regexp\"",
            "operator": "matches"
        }',
    ])]
    public function testStep1(): void
    {
        $this->assertFalse(
            true,
            '{
                "statement": {
                    "container": {
                        "value": "\"not a regexp\"",
                        "operator": "is-regexp",
                        "type": "derived-value-operation-assertion"
                    },
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$page.title matches \"not a regexp\"",
                        "index": 0,
                        "identifier": "$page.title",
                        "value": "\"not a regexp\"",
                        "operator": "matches"
                    }
                },
                "expected": true,
                "examined": "not a regexp"
            }'
        );
    }
}
