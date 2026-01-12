<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailingAction extends BasilTestCase
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
        '{
            "statement-type": "assertion",
            "source": "$page.url is \"http:\/\/www.example.com\"",
            "index": 1,
            "identifier": "$page.url",
            "value": "\"http:\/\/www.example.com\"",
            "operator": "is"            
        }',
    ])]
    public function testStep1(): void
    {
        try {
            // click $".selector"
            throw new \RuntimeException('Runtime exception executing action', 123);
        } catch (\Throwable $exception) {
            self::fail('{
                "statement": {
                    "statement-type": "action",
                    "source": "click $\".selector\"",
                    "identifier": "$\".selector\"",
                    "type": "click",
                    "arguments": "$\".selector\"",
                    "index": 0
                },
                "reason": "action-failed",
                "exception": {
                    "class": "' . addcslashes($exception::class, '"\\') . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), '"\\') . '"
                }
            }');
        }

        self::assertTrue(
            true,
            '{
                "statement": {
                    "statement-type": "assertion",
                    "source": "$page.url is \"http:\/\/www.example.com\"",
                    "identifier": "$page.url",
                    "value": "\"http:\/\/www.example.com\"",
                    "operator": "is",
                    "index": 1                
                },
                "expected": true' . ',
                "examined": true' . '                
            }'
        );
    }
}
