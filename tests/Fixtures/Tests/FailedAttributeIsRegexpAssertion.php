<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedAttributeIsRegexpAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.title matches $\".selector\".attribute_name",
            "index": 0,
            "identifier": "$page.title",
            "value": "$\".selector\".attribute_name",
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
                        "value": "$\".selector\".attribute_name",
                        "operator": "is-regexp",
                        "type": "derived-value-operation-assertion"
                    },
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$page.title matches $\".selector\".attribute_name",
                        "index": 0,
                        "identifier": "$page.title",
                        "value": "$\".selector\".attribute_name",
                        "operator": "matches"
                    }
                },
                "expected": true,
                "examined": "attribute value"
            }'
        );
    }
}
