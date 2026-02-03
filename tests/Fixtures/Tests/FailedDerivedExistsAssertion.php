<?php

declare(strict_types=1);

namespace Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;

class FailedDerivedExistsAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "action",
            "source": "click $\".selector\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "type": "click",
            "arguments": "$\".selector\""            
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "container": {
                "value": "$\".selector\"",
                "operator": "exists",
                "type": "derived-value-operation-assertion"
            },
            "statement": {
                "statement-type": "action",
                "source": "click $\".selector\"",
                "index": 0,
                "identifier": "$\".selector\"",
                "type": "click",
                "arguments": "$\".selector\""
            }
        }';

        $expected = true;
        $examined = false;

        // $".selector" exists <- click $".selector"
        // ...

        $this->assertTrue(
            $examined,
            (string) self::$messageFactory->createAssertionMessage(
                $statement_0,
                $expected,
                $examined,
            )
        );

        // click $".selector"
        // ...
    }
}
