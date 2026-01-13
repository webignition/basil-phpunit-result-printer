<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedExcludesAssertionForElement extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" excludes \"value\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"value\"",
            "operator": "excludes"
        }',
    ])]
    public function testStep1(): void
    {
        $expectedValue = 'string-not-contains-string';
        $examinedValue = 'string-not-contains-string-within';
        $this->assertStringNotContainsString(
            $expectedValue,
            $examinedValue,
            '{
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$\".selector\" excludes \"value\"",
                        "index": 0,
                        "identifier": "$\".selector\"",
                        "value": "\"value\"",
                        "operator": "excludes"               
                    },
                    "expected": "' . addcslashes((string) $expectedValue, '"\\') . '",
                    "examined": "' . addcslashes((string) $examinedValue, '"\\') . '"
                }'
        );
    }
}
