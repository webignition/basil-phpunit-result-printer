<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedDescendantCssXpathElementExistsAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\"form\" >> $\"/input\" exists",
            "index": 0,
            "identifier": "$\"form\" >> $\"/input\"",
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
                    "source": "$\"form\" >> $\"/input\" exists",
                    "index": 0,
                    "identifier": "$\"form\" >> $\"/input\"",
                    "operator": "exists"
                },
                "expected": true,
                "examined": false
            }'
        );
    }
}
