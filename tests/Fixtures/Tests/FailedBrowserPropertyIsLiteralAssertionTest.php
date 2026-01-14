<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedBrowserPropertyIsLiteralAssertionTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$browser.size is \"literal value\"",
            "index": 0,
            "identifier": "$browser.size",
            "value": "\"literal value\"",
            "operator": "is"
        }',
    ])]
    public function testStep1(): void
    {
        $expectedValue = 'literal value';
        $examinedValue = '1024x768';

        $this->assertEquals(
            $expectedValue,
            $examinedValue,
            '{
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$browser.size is \"literal value\"",
                        "index": 0,
                        "identifier": "$browser.size",
                        "value": "\"literal value\"",
                        "operator": "is"    
                    },
                    "expected": "' . addcslashes((string) $expectedValue, '"\\') . '",
                    "examined": "' . addcslashes((string) $examinedValue, '"\\') . '"
                }'
        );
    }
}
