<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedElementIsElementAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".actual\" is $\".expected\"",
            "index": 0,
            "identifier": "$\".actual\"",
            "value": "$\".expected\"",
            "operator": "is"
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$\".actual\" is $\".expected\"",
            "index": 0,
            "identifier": "$\".actual\"",
            "value": "$\".expected\"",
            "operator": "is"         
        }';

        $expected = 'expected value';
        $examined = 'actual value';

        $this->assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
