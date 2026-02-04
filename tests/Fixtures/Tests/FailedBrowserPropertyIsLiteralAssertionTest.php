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
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$browser.size is \"literal value\"",
            "index": 0,
            "identifier": "$browser.size",
            "value": "\"literal value\"",
            "operator": "is"    
        }';

        $expected = 'literal value';
        $examined = '1024x768';

        $this->assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
