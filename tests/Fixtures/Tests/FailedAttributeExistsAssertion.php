<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedAttributeExistsAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\".attribute_name exists",
            "index": 0,
            "identifier": "$\".selector\".attribute_name",
            "operator": "exists"
        }',
    ])]
    public function testStep1(): void
    {
        self::assertTrue(
            false,
            '{
                "statement": {
                    "statement-type": "assertion",
                    "source": "$\".selector\".attribute_name exists",
                    "index": 0,
                    "identifier": "$\".selector\".attribute_name",
                    "operator": "exists"
                },
                "expected": true,
                "examined": false
            }'
        );
    }
}
