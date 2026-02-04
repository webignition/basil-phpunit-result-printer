<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailedPagePropertyIsDataParameterAssertionTest extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.title is $data.expected_title",
            "index": 0,
            "identifier": "$page.title",
            "value": "$data.expected_title",
            "operator": "is"
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$page.title is $data.expected_title",
            "index": 0,
            "identifier": "$page.title",
            "value": "$data.expected_title",
            "operator": "is"        
        }';

        $expected = 'assert-equals-expected-value';
        $examined = 'assert-equals-actual-value';

        $this->assertEquals(
            $expected,
            $examined,
            (string) self::$messageFactory->createAssertionMessage($statement_0, $expected, $examined),
        );
    }
}
