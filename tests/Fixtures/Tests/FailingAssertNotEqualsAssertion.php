<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailingAssertNotEqualsAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" is-not \"value\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"value\"",
            "operator": "is-not"
        }',
    ])]
    public function testStep1(): void
    {
        $expectedValue = 'assert-not-equals-value';
        $examinedValue = 'assert-not-equals-value';

        $this->assertNotEquals(
            $expectedValue,
            $examinedValue,
            '{
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$\".selector\" is-not \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "is-not"      
                    },
                    "expected": "' . addcslashes((string) $expectedValue, '"\\') . '",
                    "examined": "' . addcslashes((string) $examinedValue, '"\\') . '"
                }'
        );
    }
}
