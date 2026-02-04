<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BaseBasilTestCase\Enum\StatementStage;

class FailedClickAction extends BasilTestCase
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
            "statement-type": "action",
            "source": "click $\".selector\"",
            "identifier": "$\".selector\"",
            "type": "click",
            "arguments": "$\".selector\"",
            "index": 0
        }';

        try {
            // click $".selector"
            throw new \RuntimeException('Runtime exception executing action', 123);
        } catch (\Throwable $exception) {
            $this->fail(
                (string) self::$messageFactory->createFailureMessage(
                    $statement_0,
                    $exception,
                    StatementStage::EXECUTE,
                ),
            );
        }
    }
}
