<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedElementMatchesLiteralAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" matches \"\/^value\/\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"\/^value\/\"",
            "operator": "matches"
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$\".selector\" matches \"\/^value\/\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"\/^value\/\"",
            "operator": "matches"               
        }';

        $expected = '/pattern/';
        $examined = 'assert-matches-regular-expression-expected-value';

        $this->assertMatchesRegularExpression(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
