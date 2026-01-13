<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedIsAssertionForElement extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" is \"value\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"value\"",
            "operator": "is"
        }',
    ])]
    public function testStep1(): void
    {
        $expectedValue = 'assert-equals-expected-value';
        $examinedValue = 'assert-equals-actual-value';

        $this->assertEquals(
            $expectedValue,
            $examinedValue,
            '{
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$\".selector\" is \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "is"          
                    },
                    "expected": "' . addcslashes((string) $expectedValue, '"\\') . '",
                    "examined": "' . addcslashes((string) $examinedValue, '"\\') . '"
                }'
        );
    }
}
