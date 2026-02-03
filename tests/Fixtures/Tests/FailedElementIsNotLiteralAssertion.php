<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedElementIsNotLiteralAssertion extends BasilTestCase
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
        $statement_0 = ' {
            "statement-type": "assertion",
            "source": "$\".selector\" is-not \"value\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"value\"",
            "operator": "is-not"      
        }';

        $expected = 'assert-not-equals-value';
        $examined = 'assert-not-equals-value';

        $this->assertNotEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
