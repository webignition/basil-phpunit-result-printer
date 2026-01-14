<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedDescendantCssCssElementExistsAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\"form\":3 >> $\"input\":2 exists",
            "index": 0,
            "identifier": "$\"form\":3 >> $\"input\":2",
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
                    "source": "$\"form\":3 >> $\"input\":2 exists",
                    "index": 0,
                    "identifier": "$\"form\":3 >> $\"input\":2",
                    "operator": "exists"
                },
                "expected": true,
                "examined": false
            }'
        );
    }
}
